<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Unit;

trait BelongsToManyUnit
{
    /**
     * Get the units.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function units()
    {
        return $this->belongsToMany(Unit::class);
    }
}
