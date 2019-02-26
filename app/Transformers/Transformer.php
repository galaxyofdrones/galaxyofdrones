<?php

namespace Koodilab\Transformers;

use Koodilab\Contracts\Transformers\Transformer as TransformerContract;

abstract class Transformer implements TransformerContract
{
    /**
     * {@inheritdoc}
     */
    public function transformCollection($items)
    {
        foreach ($items as $i => $item) {
            $items[$i] = $this->transform($item);
        }

        return $items;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function transform($item);
}
