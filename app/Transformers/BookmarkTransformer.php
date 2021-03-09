<?php

namespace App\Transformers;

class BookmarkTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \App\Models\Bookmark $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'x' => $item->star->x,
            'y' => $item->star->y,
            'created_at' => $item->created_at->toDateTimeString(),
        ];
    }
}
