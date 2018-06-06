<?php

namespace Koodilab\Models\Transformers;

use Koodilab\Models\Planet;

class MovementTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\Movement $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'type' => $item->type,
            'remaining' => $item->remaining,
            'start' => $this->planet($item->start),
            'end' => $this->planet($item->end),
        ];
    }

    /**
     * Get the planet.
     *
     * @param Planet $planet
     *
     * @return array
     */
    protected function planet(Planet $planet)
    {
        return [
            'id' => $planet->id,
            'resource_id' => $planet->resource_id,
            'display_name' => $planet->display_name,
            'x' => $planet->x,
            'y' => $planet->y,
        ];
    }
}
