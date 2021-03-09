<?php

namespace App\Transformers;

class UserTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \App\Models\User $item
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
            'is_notification_enabled' => $item->is_notification_enabled,
            'gravatar' => $item->gravatar([
                's' => 100,
            ]),
        ];
    }
}
