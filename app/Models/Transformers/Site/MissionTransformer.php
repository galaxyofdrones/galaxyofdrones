<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Mission;
use Koodilab\Models\Resource;
use Koodilab\Models\Transformers\Transformer;

class MissionTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param Mission $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'energy' => $item->energy,
            'experience' => $item->experience,
            'remaining' => $item->remaining,
            'resources' => $this->resources($item),
        ];
    }

    /**
     * Get the units.
     *
     * @param Mission $item
     *
     * @return array
     */
    protected function resources(Mission $item)
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
