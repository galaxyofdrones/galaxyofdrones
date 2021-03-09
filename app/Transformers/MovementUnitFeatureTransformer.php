<?php

namespace App\Transformers;

use App\Models\Movement;

class MovementUnitFeatureTransformer extends MovementFeatureTransformer
{
    /**
     * {@inheritdoc}
     *
     * @param Movement $item
     */
    public function transform($item)
    {
        $current = $item->remaining / max(1, $item->created_at->diffInSeconds(
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
                'is_movement' => true,
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
