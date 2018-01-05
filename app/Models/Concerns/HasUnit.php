<?php

namespace Koodilab\Models\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Koodilab\Models\Building;
use Koodilab\Models\Unit;

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

        if (!$this->building_id || $this->building->type != Building::TYPE_TRAINER) {
            return $units;
        }

        if ($this->training) {
            return $units->add(
                $this->training->unit
            );
        }

        $this->building->applyModifiers([
            'level' => $this->level,
        ]);

        return $this->planet->user->findUnitsOrderBySortOrder()
            ->transform(function (Unit $unit) {
                return $unit->applyModifiers([
                    'train_time_bonus' => $this->building->train_time_bonus,
                ]);
            });
    }
}
