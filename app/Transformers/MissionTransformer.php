<?php

namespace Koodilab\Transformers;

use Koodilab\Models\Mission;
use Koodilab\Models\Resource;

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
     * Get the resources.
     *
     * @param Mission $mission
     *
     * @return array
     */
    protected function resources(Mission $mission)
    {
        return $mission->resources->transform(function (Resource $resource) {
            return [
                'id' => $resource->id,
                'name' => $resource->translation('name'),
                'description' => $resource->translation('description'),
                'quantity' => $resource->pivot->quantity,
            ];
        });
    }
}
