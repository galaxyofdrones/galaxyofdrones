<?php

namespace Koodilab\Models\Transformers;

use Koodilab\Models\Movement;

class MovementUnitFeatureTransformer extends MovementFeatureTransformer
{
    /**
     * {@inheritdoc}
     *
     * @param Movement $item
     */
    public function transform($item)
    {
        $current = $item->remaining / max(0, $item->created_at->diffInSeconds(
            $item->ended_at, false
        ));

        $startX = round(
            $current * $item->start->x + (1 - $current) * $item->end->x
        );

        $startY = round(
            $current * $item->start->y + (1 - $current) * $item->end->y
        );

        return [
            'type' => 'Feature',
            'properties' => [
                'isMovement' => true,
                'type' => $item->type,
                'status' => $this->status($item),
                'interval' => $item->remaining,
                'end' => [
                    $item->end->x,
                    $item->end->y,
                ],
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    $startX,
                    $startY,
                ],
            ],
        ];
    }
}
