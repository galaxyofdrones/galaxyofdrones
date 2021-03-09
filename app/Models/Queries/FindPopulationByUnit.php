<?php

namespace App\Models\Queries;

use App\Models\Unit;

trait FindPopulationByUnit
{
    /**
     * Find the population by unit.
     *
     * @param array $columns
     *
     * @return \App\Models\Population
     */
    public function findPopulationByUnit(Unit $unit, $columns = ['*'])
    {
        /** @var \App\Models\Population $population */
        $population = $this->populations()
            ->where('unit_id', $unit->id)
            ->first($columns);

        if ($population) {
            $population->setRelation('unit', $unit);
        }

        return $population;
    }
}
