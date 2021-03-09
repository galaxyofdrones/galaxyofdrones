<?php

namespace App\Models\Relations;

use App\Models\Planet;

trait HasManyPlanet
{
    /**
     * Get the planets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planets()
    {
        return $this->hasMany(Planet::class);
    }
}
