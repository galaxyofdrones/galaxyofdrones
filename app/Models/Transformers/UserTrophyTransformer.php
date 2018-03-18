<?php

namespace Koodilab\Models\Transformers;

class UserTrophyTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\User $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'username' => $item->username,
            'experience' => $item->experience,
            'mission_count' => $item->mission_logs_count,
            'expedition_count' => $item->expedition_logs_count,
            'planet_count' => $item->planets_count,
            'winning_battle_count' => $item->winning_battle_count,
            'losing_battle_count' => $item->losing_battle_count,
        ];
    }
}
