<?php

namespace App\Models\Relations;

use App\Models\Unit;

trait BelongsToUnit
{
    /**
     * Get the unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
