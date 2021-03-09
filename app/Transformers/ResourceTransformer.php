<?php

namespace App\Transformers;

class ResourceTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \App\Models\Resource $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->translation('name'),
            'frequency' => $item->frequency,
            'efficiency' => $item->efficiency,
            'description' => $item->translation('description'),
            'research_experience' => $item->research_experience,
            'research_cost' => $item->research_cost,
            'research_time' => $item->research_time,
        ];
    }
}
