<?php

namespace App\Models\Queries;

use App\Models\Unit;

trait FindUnitsOrderBySortOrder
{
    /**
     * Find units order by sort order.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|Unit[]
     */
    public function findUnitsOrderBySortOrder($columns = ['*'])
    {
        return $this->units()
            ->orderBy('sort_order')
            ->get($columns);
    }
}
