<?php

namespace Koodilab\Transformers;

class StarFeatureTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\Star $item
     */
    public function transform($item)
    {
        return [
            'type' => 'Feature',
            'properties' => [
                'id' => $item->id,
                'name' => $item->name,
                'type' => 'star',
                'size' => 96,
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    $item->x, $item->y,
                ],
            ],
        ];
    }
}
