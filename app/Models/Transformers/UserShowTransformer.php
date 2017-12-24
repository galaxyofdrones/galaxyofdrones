<?php

namespace Koodilab\Models\Transformers;

class UserShowTransformer extends Transformer
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
        ];
    }
}
