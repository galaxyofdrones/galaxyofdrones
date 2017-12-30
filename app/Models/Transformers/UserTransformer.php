<?php

namespace Koodilab\Models\Transformers;

class UserTransformer extends Transformer
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
            'email' => $item->email,
            'energy' => $item->energy,
            'production_rate' => $item->production_rate,
            'level' => $item->level,
            'experience' => $item->experience,
            'level_experience' => $item->level_experience,
            'next_level_experience' => $item->next_level_experience,
            'notification_count' => $item->notifications()->count(),
            'gravatar' => $item->gravatar([
                's' => 100,
            ]),
        ];
    }
}
