<?php

namespace Koodilab\Models\Transformers;

use Koodilab\Models\Movement;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;

class MovementScoutTransformer extends MovementTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($item)
    {
        return array_merge(parent::transform($item), [
            'resources' => $this->resources($item),
            'units' => $this->units($item),
        ]);
    }

    /**
     * Get the units.
     *
     * @param Movement $item
     *
     * @return array
     */
    protected function units(Movement $item)
    {
        return $item->findUnitsOrderBySortOrder()
            ->transform(function (Unit $unit) {
                return [
                    'id' => $unit->id,
                    'name' => $unit->translation('name'),
                    'description' => $unit->translation('description'),
                    'quantity' => $unit->pivot->quantity,
                ];
            });
    }

    /**
     * Get the resources.
     *
     * @param Movement $item
     *
     * @return array
     */
    protected function resources(Movement $item)
    {
        return $item->findResourcesOrderBySortOrder()
            ->transform(function (Resource $resource) {
                return [
                    'id' => $resource->id,
                    'name' => $resource->translation('name'),
                    'description' => $resource->translation('description'),
                    'quantity' => $resource->pivot->quantity,
                ];
            });
    }
}
