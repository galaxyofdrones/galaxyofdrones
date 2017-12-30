<?php

namespace Koodilab\Models\Transformers;

use Koodilab\Models\BattleLog;

class BattleLogTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param BattleLog $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
        ];
    }
}
