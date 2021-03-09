<?php

namespace App\Models\Concerns;

use App\Models\Building;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;

trait HasUnit
{
    /**
     * Get the training units.
     *
     * @return Collection|Unit[]
     */
    public function trainingUnits()
    {
        $units = new Collection();

        if (! $this->building_id || $this->building->type != Building::TYPE_TRAINER) {
            return $units;
        }

        $this->building->applyModifiers([
            'level' => $this->level,
        ]);

        if ($this->training) {
            return $units->add(
                $this->training->unit->applyModifiers([
                    'train_time_bonus' => $this->building->train_time_bonus,
                    'train_cost_penalty' => $this->planet->user->penalty_rate,
                ])
            );
        }

        return $this->planet->user->findUnitsOrderBySortOrder()
            ->transform(function (Unit $unit) {
                return $unit->applyModifiers([
                    'train_time_bonus' => $this->building->train_time_bonus,
                    'train_cost_penalty' => $this->planet->user->penalty_rate,
                ]);
            });
    }
}
