<?php

namespace App\Transformers;

use App\Models\Expedition;

class ExpeditionFeatureTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param Expedition $item
     */
    public function transform($item)
    {
        return [
            'type' => 'Feature',
            'properties' => [
                'type' => 'expedition',
            ],
            'geometry' => [
                'type' => 'LineString',
                'coordinates' => [
                    [
                        $item->user->capital->x,
                        $item->user->capital->y,
                    ],
                    [
                        $item->star->x,
                        $item->star->y,
                    ],
                ],
            ],
        ];
    }
}
