<?php

namespace App\Models\Relations;

use App\Models\Planet;

trait BelongsToPlanet
{
    /**
     * Get the planet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }
}
