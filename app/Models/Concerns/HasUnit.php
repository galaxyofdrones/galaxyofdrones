<?php

namespace Koodilab\Models\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Koodilab\Models\Building;

trait HasUnit
{
    /**
     * Get the training units.
     *
     * @return Collection|\Koodilab\Models\Unit[]
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

        return $this->planet->user->findAllUnitsOrderBySortOrder();
    }
}
