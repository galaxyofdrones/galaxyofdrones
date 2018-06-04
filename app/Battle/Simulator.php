<?php

namespace Koodilab\Battle;

use Koodilab\Contracts\Battle\Simulator as SimulatorContract;
use Koodilab\Game\BattleManager;
use Koodilab\Game\StorageManager;
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
     * The battle manager instance.
     *
     * @var BattleManager
     */
    protected $battleManager;

    /**
     * The storage manager instance.
     *
     * @var StorageManager
     */
    protected $storageManager;

    /**
     * Constructor.
     *
     * @param BattleManager  $battleManager
     * @param StorageManager $storageManager
     */
    public function __construct(BattleManager $battleManager, StorageManager $storageManager)
    {
        $this->battleManager = $battleManager;
        $this->storageManager = $storageManager;
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
                $population->unit->applyModifiers([
                    'defense_bonus' => $this->movement->end->defense_bonus,
                ]);

                return $population;
            });

        $this->grids = $this->movement->end->grids()
            ->with('building')
            ->whereNotNull('building_id')
            ->get()
            ->sortByDesc('building.type')
            ->map(function (Grid $grid) {
                $grid->setRelation('planet', $this->movement->end);
                $grid->building->applyModifiers([
                    'level' => $grid->level,
                    'defense_bonus' => $this->movement->end->defense_bonus,
                ]);

                return $grid;
            });
    }

    /**
     * {@inheritdoc}
     */
    public function scout(Movement $movement)
    {
        $this->setup($movement);

        if ($this->attackerDetection() < $this->defenderDetection()) {
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
    protected function attackerDetection()
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
    protected function defenderDetection()
    {
        $detection = $this->movement->end->populations->reduce(function ($carry, Population $population) {
            return $carry + $population->unit->detection * $population->quantity;
        }, 0);

        if ($this->movement->end->isCapital()) {
            $detection = $this->movement->end->user->units->reduce(function ($carry, Unit $unit) {
                return $carry + $unit->detection * $unit->pivot->quantity;
            }, $detection);
        }

        return $this->grids->reduce(function ($carry, Grid $grid) {
            return $carry + $grid->building->detection;
        }, $detection);
    }

    /**
     * Get the attacker attack.
     *
     * @return int
     */
    protected function attackerAttack()
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
    protected function defenderDefense()
    {
        $defense = $this->movement->end->populations->reduce(function ($carry, Population $population) {
            return $carry + $population->unit->defense * $population->quantity;
        }, 0);

        if ($this->movement->end->isCapital()) {
            $defense = $this->movement->end->user->units->reduce(function ($carry, Unit $unit) {
                return $carry + $unit->defense * $unit->pivot->quantity;
            }, $defense);
        }

        return $this->grids->reduce(function ($carry, Grid $grid) {
            return $carry + $grid->building->defense;
        }, $defense);
    }

    /**
     * Battle.
     */
    protected function battle()
    {
        $defense = $this->defenderDefense();

        if (! $defense) {
            $this->attackerLossRate = 0;
            $this->defenderLossRate = 1;
        } else {
            $result = $this->attackerAttack() / $defense;

            if ($result < 1) {
                $this->attackerLossRate = 1;
                $this->defenderLossRate = sqrt($result) * $result;
            } else {
                $this->attackerLossRate = sqrt(1 / $result) / $result;
                $this->defenderLossRate = 1;
            }
        }

        $this->battleLog = $this->battleManager->createLog(
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
        $this->battleLog = $this->battleManager->createLog($this->movement);

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
                'owner' => BattleLog::OWNER_ATTACKER,
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
            $quantity = $population->quantity;

            if ($population->planet->isCapital()) {
                $userUnit = $population->planet->user->units->firstWhere('id', $population->unit_id);

                if ($userUnit) {
                    $quantity += $userUnit->pivot->quantity;
                }
            }

            if ($quantity) {
                $losses = round($quantity * $this->defenderLossRate);

                $this->battleLog->defenderUnits()->attach($population->unit_id, [
                    'owner' => BattleLog::OWNER_DEFENDER,
                    'quantity' => $quantity,
                    'losses' => $losses,
                ]);

                if (! empty($losses)) {
                    $this->storageManager->decrementPopulation(
                        $population->planet, $population->unit, $losses
                    );
                }
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
            $capacity = min(
                $total, round($this->capacity * $this->defenderLossRate)
            );

            foreach ($this->stocks as $stock) {
                $quantity = $stock->quantity;

                if ($quantity) {
                    $losses = round($capacity * ($quantity / $total));

                    if (! empty($losses) || $this->battleLog->type == BattleLog::TYPE_SCOUT) {
                        $this->battleLog->resources()->attach($stock->resource_id, [
                            'quantity' => $quantity,
                            'losses' => $losses,
                        ]);

                        if (! empty($losses)) {
                            $stock->decrementQuantity($losses);
                        }
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

            if (! empty($losses) || $grid->building->type == Building::TYPE_DEFENSIVE || $this->battleLog->type == BattleLog::TYPE_SCOUT) {
                $this->battleLog->buildings()->attach($grid->building_id, [
                    'level' => $level,
                    'losses' => $losses,
                ]);

                if (! empty($losses)) {
                    $grid->demolishBuilding($losses);
                }
            }

            $damage -= $losses;
        }
    }
}
