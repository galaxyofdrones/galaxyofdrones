<?php

namespace Koodilab\Models\Transformers;

abstract class Transformer
{
    /**
     * Transform the collection.
     *
     * @param mixed $items
     *
     * @return array
     */
    public function transformCollection($items)
    {
        foreach ($items as $i => $item) {
            $items[$i] = $this->transform($item);
        }

        return $items;
    }

    /**
     * Transform the item.
     *
     * @param mixed $item
     *
     * @return array
     */
    abstract public function transform($item);
}
