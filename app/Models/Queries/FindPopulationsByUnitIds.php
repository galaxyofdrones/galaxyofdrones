<?php

namespace Koodilab\Models\Queries;

trait FindPopulationsByUnitIds
{
    /**
     * Find populations by unit ids.
     *
     * @param array $unitIds
     * @param array $columns
     *
     * @return \Koodilab\Models\Population[]
     */
    public function findPopulationsByUnitIds($unitIds, $columns = ['*'])
    {
        return $this->populations()
            ->with('unit')
            ->whereIn('unit_id', $unitIds)
            ->get($columns);
    }
}
