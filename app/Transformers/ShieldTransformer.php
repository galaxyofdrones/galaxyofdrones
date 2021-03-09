<?php

namespace App\Transformers;

use App\Models\Shield;

class ShieldTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \App\Models\Shield $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'remaining' => $item->remaining,
            'planet' => $this->planet($item),
        ];
    }

    /**
     * Get the planet.
     *
     * @return array
     */
    protected function planet(Shield $shield)
    {
        return [
            'id' => $shield->planet->id,
            'resource_id' => $shield->planet->resource_id,
            'name' => $shield->planet->display_name,
            'x' => $shield->planet->x,
            'y' => $shield->planet->y,
        ];
    }
}
