<?php

namespace App\Models\Queries;

trait FindPlanetsOrderByName
{
    /**
     * Find planets order by name.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Planet[]
     */
    public function findPlanetsOrderByName($columns = ['*'])
    {
        return $this->planets()
            ->orderByRaw('IFNULL(custom_name, name)')
            ->get($columns);
    }
}
