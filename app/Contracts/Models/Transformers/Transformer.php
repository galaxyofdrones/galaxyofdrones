<?php

namespace Koodilab\Contracts\Models\Transformers;

interface Transformer
{
    /**
     * Transform the collection.
     *
     * @param mixed $items
     *
     * @return array
     */
    public function transformCollection($items);

    /**
     * Transform the item.
     *
     * @param mixed $item
     *
     * @return array
     */
    public function transform($item);
}
