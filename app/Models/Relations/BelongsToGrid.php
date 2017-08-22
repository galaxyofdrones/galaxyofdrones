<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Grid;

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
