<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Unit;

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
