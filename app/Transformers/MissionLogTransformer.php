<?php

namespace App\Transformers;

use App\Models\MissionLog;
use App\Models\Resource;

class MissionLogTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param MissionLog $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'energy' => $item->energy,
            'experience' => $item->experience,
            'created_at' => $item->created_at->toDateTimeString(),
            'resources' => $this->resources($item),
        ];
    }

    /**
     * Get the resources.
     *
     * @return array
     */
    protected function resources(MissionLog $missionLog)
    {
        return $missionLog->resources->transform(function (Resource $resource) {
            return [
                'id' => $resource->id,
                'name' => $resource->translation('name'),
                'description' => $resource->translation('description'),
                'quantity' => $resource->pivot->quantity,
            ];
        });
    }
}
