<?php

namespace App\Models\Relations;

use App\Models\Grid;

trait BelongsToGrid
{
    /**
     * Get the grid.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grid()
    {
        return $this->belongsTo(Grid::class);
    }
}
