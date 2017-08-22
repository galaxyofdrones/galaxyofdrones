<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Unit;

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
