<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Population;

trait HasManyPopulation
{
    /**
     * Get the populations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function populations()
    {
        return $this->hasMany(Population::class);
    }
}
