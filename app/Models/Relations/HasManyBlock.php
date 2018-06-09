<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Block;

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
