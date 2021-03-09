<?php

namespace App\Models\Queries;

trait FindPopulationsByUnitIds
{
    /**
     * Find populations by unit ids.
     *
     * @param array $unitIds
     * @param array $columns
     *
     * @return \App\Models\Population[]
     */
    public function findPopulationsByUnitIds($unitIds, $columns = ['*'])
    {
        return $this->populations()
            ->with('unit')
            ->whereIn('unit_id', $unitIds)
            ->get($columns);
    }
}
