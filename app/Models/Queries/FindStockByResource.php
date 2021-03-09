<?php

namespace App\Models\Queries;

use App\Models\Resource;

trait FindStockByResource
{
    /**
     * Find stock.
     *
     * @param array $columns
     *
     * @return \App\Models\Stock
     */
    public function findStockByResource(Resource $resource, $columns = ['*'])
    {
        /** @var \App\Models\Stock $stock */
        $stock = $this->stocks()
            ->where('resource_id', $resource->id)
            ->first($columns);

        if ($stock) {
            $stock->setRelation('planet', $this);
        }

        return $stock;
    }
}
