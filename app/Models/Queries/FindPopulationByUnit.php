<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Unit;

trait FindPopulationByUnit
{
    /**
     * Find the population by unit.
     *
     * @param Unit  $unit
     * @param array $columns
     *
     * @return \Koodilab\Models\Population
     */
    public function findPopulationByUnit(Unit $unit, $columns = ['*'])
    {
        /** @var \Koodilab\Models\Population $population */
        $population = $this->populations()
            ->where('unit_id', $unit->id)
            ->first($columns);

        if ($population) {
            $population->setRelation('unit', $unit);
        }

        return $population;
    }
}
