<?php

namespace Koodilab\Models\Queries;

trait FindResourcesOrderBySortOrder
{
    /**
     * Find resources order by sort order.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Resource[]
     */
    public function findResourcesOrderBySortOrder($columns = ['*'])
    {
        return $this->resources()
            ->orderBy('sort_order')
            ->get($columns);
    }
}
