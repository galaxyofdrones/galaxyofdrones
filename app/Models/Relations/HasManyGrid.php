<?php

namespace App\Models\Relations;

use App\Models\Grid;

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
