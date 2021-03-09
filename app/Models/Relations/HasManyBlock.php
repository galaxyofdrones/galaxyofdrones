<?php

namespace App\Models\Relations;

use App\Models\Block;

trait HasManyBlock
{
    /**
     * Get the blocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blocks()
    {
        return $this->hasMany(Block::class);
    }
}
