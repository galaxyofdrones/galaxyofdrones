<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Transformers\Transformer;
use Koodilab\Models\User;

class UserTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param User $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'username' => $item->username,
            'energy' => $item->energy,
            'production_rate' => $item->production_rate,
            'level' => $item->level,
            'experience' => $item->experience,
            'level_experience' => $item->level_experience,
            'next_level_experience' => $item->next_level_experience,
            'gravatar' => $item->gravatar([
                's' => 100,
            ]),
        ];
    }
}
