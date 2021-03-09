<?php

namespace App\Models\Queries;

trait FindGrids
{
    /**
     * Find grids.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Grid[]
     */
    public function findGrids($columns = ['*'])
    {
        return $this->grids()
            ->with('construction', 'upgrade')
            ->get($columns);
    }
}
