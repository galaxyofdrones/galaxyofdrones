<?php

namespace Koodilab\Models\Queries;

trait FindPlanetsOrderByName
{
    /**
     * Find planets order by name.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Planet[]
     */
    public function findPlanetsOrderByName($columns = ['*'])
    {
        return $this->planets()
            ->orderByRaw('CONCAT(custom_name, name)')
            ->get($columns);
    }
}
