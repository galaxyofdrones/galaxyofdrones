<?php

namespace Koodilab\Models\Queries;

trait FindStock
{
    /**
     * Find stock.
     *
     * @param resource $resource
     * @param array    $columns
     *
     * @return \Koodilab\Models\Stock
     */
    public function findStock(Resource $resource, $columns = ['*'])
    {
        /** @var \Koodilab\Models\Stock $stock */
        $stock = $this->stocks()
            ->where('resource_id', $resource->id)
            ->first($columns);

        if ($stock) {
            $stock->setRelation('planet', $this);
        }

        return $stock;
    }
}
