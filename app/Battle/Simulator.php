<?php

namespace Koodilab\Battle;

use Koodilab\Contracts\Battle\Simulator as SimulatorContract;
use Koodilab\Models\BattleLog;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Movement;
use Koodilab\Models\Population;
use Koodilab\Models\Stock;
use Koodilab\Models\Unit;

class Simulator implements SimulatorContract
{
    /**
     * The movement instance.
     *
     * @var Movement
     */
    protected $movement;

    /**
     * The stocks.
     *
     * @var \Illuminate\Database\Eloquent\Collection|Stock[]
     */
    protected $stocks;

    /**
     * The populations.
     *
     * @var \Illuminate\Database\Eloquent\Collection|Population[]
     */
    protected $populations;

    /**
     * The grids.
     *
     * @var \Illuminate\Database\Eloquent\Collection|Grid[]
     */
    protected $grids;

    /**
     * The battle log instance.
     *
     * @var BattleLog
     */
    protected $battleLog;

    /**
     * The attacker loss rate.
     *
     * @var float
     */
    protected $attackerLossRate;

    /**
     * The defender loss rate.
     *
     * @var float
     */
    protected $defenderLossRate;

    /**
     * The capacity.
     *
     * @var int
     */
    protected $capacity;

    /**
     * The heavy fighter count.
     *
     * @var int
     */
    protected $heavyFighterCount;

    /**
     * {@inheritdoc}
     */
    public function scout(Movement $movement)
    {
        $this->setup($movement);

        if ($this->getAttackerDetection() < $this->getDefenderDetection()) {
            $this->battle();
        } else {
            $this->report();
        }

        return $this->battleLog;
    }

    /**
     * {@inheritdoc}
     */
    public function attack(Movement $movement)
    {
        $this->setup($movement);
        $this->battle();

        return $this->battleLog;
    }

    /**
     * {@inheritdoc}
     */
    public function occupy(Movement $movement)
    {
        $this->setup($movement);
        $this->battle();

        return $this->battleLog;
    }

    /**
     * Get the attacker detection.
     *
     * @return int
     */
    protected function getAttackerDetection()
    {
        return $this->movement->units->reduce(function ($carry, Unit $unit) {
            return $carry + $unit->detection * $unit->pivot->quantity;
        }, 0);
    }

    /**
     * Get the defender detection.
     *
     * @return int
     */
    protected function getDefenderDetection()
    {
        $detection = $this->movement->end->populations->reduce(function ($carry, Population $population) {
            return $carry + $population->unit->detection * $population->quantity;
        }, 0);

        return $this->grids->reduce(function ($carry, Grid $grid) {
            return $carry + $grid->building->detection;
        }, $detection);
    }

    /**
     * Get the attacker attack.
     *
     * @return int
     */
    protected function getAttackerAttack()
    {
        return $this->movement->units->reduce(function ($carry, Unit $unit) {
            return $carry + $unit->attack * $unit->pivot->quantity;
        }, 0);
    }

    /**
     * Get the defender defense.
     *
     * @return int
     */
    protected function getDefenderDefense()
    {
        $defense = $this->movement->end->populations->reduce(function ($carry, Population $population) {
            return $carry + $population->unit->defense * $population->quantity;
        }, 0);

        return $this->grids->reduce(function ($carry, Grid $grid) {
            return $carry + $grid->building->defense;
        }, $defense);
    }

    /**
     * Setup.
     *
     * @param Movement $movement
     */
    protected function setup(Movement $movement)
    {
        $this->movement = $movement;

        $this->movement->start->load('user');
        $this->movement->end->load('user');

        $this->stocks = $this->movement->end->stocks()
            ->get()
            ->map(function (Stock $stock) {
                return $stock->setRelation('planet', $this->movement->end);
            });

        $this->populations = $this->movement->end->populations()
            ->with('unit')
            ->get()
            ->map(function (Population $population) {
                $population->setRelation('planet', $this->movement->end);
                $population->unit->applyModifiers($this->movement->end->defense_bonus);

                return $population;
            });

        $this->grids = $this->movement->end->grids()
            ->with('building')
            ->whereNotNull('building_id')
            ->get()
            ->sortByDesc('building.type')
            ->map(function (Grid $grid) {
                $grid->setRelation('planet', $this->movement->end);
                $grid->building->applyModifiers($grid->level, $this->movement->end->defense_bonus);

                return $grid;
            });
    }

    /**
     * Battle.
     */
    protected function battle()
    {
        $defense = $this->getDefenderDefense();

        if (!$defense) {
            $this->attackerLossRate = 0;
            $this->defenderLossRate = 1;
        } else {
            $result = $this->getAttackerAttack() / $defense;

            if ($result < 1) {
                $this->attackerLossRate = 1;
                $this->defenderLossRate = sqrt($result) * $result;
            } else {
                $this->attackerLossRate = sqrt(1 / $result) / $result;
                $this->defenderLossRate = 1;
            }
        }

        $this->battleLog = BattleLog::createFromMovement(
            $this->movement, $this->attackerLossRate > $this->defenderLossRate
                ? BattleLog::WINNER_DEFENDER
                : BattleLog::WINNER_ATTACKER
        );

        $this->calculate();
    }

    /**
     * Report.
     */
    protected function report()
    {
        $this->attackerLossRate = 0;
        $this->defenderLossRate = 0;

        $this->battleLog = BattleLog::createFromMovement($this->movement);

        $this->calculate();
    }

    /**
     * Calculate.
     */
    protected function calculate()
    {
        $this->capacity = 0;
        $this->heavyFighterCount = 0;

        $this->calculateAttackerUnits();
        $this->calculateDefenderUnits();

        $this->calculateResources();
        $this->calculateBuildings();
    }

    /**
     * Calculate the attacker units.
     */
    protected function calculateAttackerUnits()
    {
        foreach ($this->movement->units as $unit) {
            $losses = round($unit->pivot->quantity * $this->attackerLossRate);

            $this->battleLog->attackerUnits()->attach($unit->id, [
                'quantity' => $unit->pivot->quantity,
                'losses' => $losses,
            ]);

            $survivor = $unit->pivot->quantity - $losses;
            $this->capacity += $unit->capacity * $survivor;

            if ($unit->type == Unit::TYPE_HEAVY_FIGHTER) {
                $this->heavyFighterCount += $survivor;
            }
        }
    }

    /**
     * Calculate the defender units.
     */
    protected function calculateDefenderUnits()
    {
        foreach ($this->populations as $population) {
            if ($quantity = $population->quantity) {
                $population->decrementQuantity(round($quantity * $this->defenderLossRate));

                $this->battleLog->defenderUnits()->attach($population->unit_id, [
                    'quantity' => $quantity,
                    'losses' => $quantity - $population->quantity,
                ]);
            }
        }
    }

    /**
     * Calculate the resources.
     */
    protected function calculateResources()
    {
        $total = $this->stocks->reduce(function ($carry, Stock $stock) {
            return $carry + $stock->quantity;
        }, 0);

        if ($total) {
            $capacity = round($this->capacity * $this->defenderLossRate);

            foreach ($this->stocks as $stock) {
                if ($quantity = $stock->quantity) {
                    $stock->decrementQuantity(round($capacity * ($quantity / $total)));
                    $losses = $quantity - $stock->quantity;

                    if ($losses || $this->battleLog->type == BattleLog::TYPE_SCOUT) {
                        $this->battleLog->resources()->attach($stock->resource_id, [
                            'quantity' => $quantity,
                            'losses' => $losses,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Calculate the buildings.
     */
    protected function calculateBuildings()
    {
        $damage = round($this->heavyFighterCount * $this->defenderLossRate);

        foreach ($this->grids as $grid) {
            $level = $grid->level;
            $losses = min($damage, $level);

            if ($losses || $grid->building->type == Building::TYPE_DEFENSIVE || $this->battleLog->type == BattleLog::TYPE_SCOUT) {
                $grid->decrementLevel($losses);

                $this->battleLog->buildings()->attach($grid->building_id, [
                    'level' => $level,
                    'losses' => $losses,
                ]);
            }

            $damage -= $losses;
        }
    }

    /**
     * Get a random float.
     *
     * @return float
     */
    protected function getRandFloat()
    {
        return (float) mt_rand() / (float) mt_getrandmax();
    }
}
