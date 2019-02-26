<?php

namespace Koodilab\Transformers;

class RankTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\Rank $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'username' => $item->username,
            'experience' => $item->experience,
            'mission_count' => $item->mission_count,
            'expedition_count' => $item->expedition_count,
            'planet_count' => $item->planet_count,
            'winning_battle_count' => $item->winning_battle_count,
            'losing_battle_count' => $item->losing_battle_count,
        ];
    }
}
