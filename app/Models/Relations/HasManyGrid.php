<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Grid;

trait HasManyGrid
{
    /**
     * Get the grids.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grids()
    {
        return $this->hasMany(Grid::class);
    }
}
