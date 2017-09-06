<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Planet;
use Koodilab\Models\Transformers\Transformer;

class CurrentPlanetTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param Planet $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->display_name,
            'x' => $item->x,
            'y' => $item->y,
            'capacity' => $item->capacity,
            'supply' => $item->supply,
            'mining_rate' => $item->mining_rate,
            'production_rate' => $item->production_rate,
            'defense_bonus' => $item->defense_bonus,
            'construction_time_bonus' => $item->construction_time_bonus,
            'used_capacity' => $item->used_capacity,
            'used_supply' => $item->used_supply,
            'used_training_supply' => $item->used_training_supply,
        ];
    }
}
