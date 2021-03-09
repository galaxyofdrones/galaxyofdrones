<?php

namespace App\Transformers;

class UnitTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \App\Models\Unit $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->translation('name'),
            'type' => $item->type,
            'speed' => $item->speed,
            'attack' => $item->attack,
            'defense' => $item->defense,
            'supply' => $item->supply,
            'train_cost' => $item->train_cost,
            'train_time' => $item->train_time,
            'description' => $item->translation('description'),
            'detection' => $item->detection,
            'capacity' => $item->capacity,
            'research_experience' => $item->research_experience,
            'research_cost' => $item->research_cost,
            'research_time' => $item->research_time,
        ];
    }
}
