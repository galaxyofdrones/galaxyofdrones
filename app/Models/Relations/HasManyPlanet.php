<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Planet;

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
