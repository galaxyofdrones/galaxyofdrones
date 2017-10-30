<?php

namespace Koodilab\Models\Queries;

trait FindStocksByResourceIds
{
    /**
     * Find stocks by resource ids.
     *
     * @param array $resourceIds
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Stock[]
     */
    public function findStocksByResourceIds($resourceIds, $columns = ['*'])
    {
        return $this->stocks()
            ->whereIn('resource_id', $resourceIds)
            ->get($columns);
    }
}
