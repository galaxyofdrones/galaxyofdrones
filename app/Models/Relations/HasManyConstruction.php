<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Construction;

trait HasManyConstruction
{
    /**
     * Get the constructions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function constructions()
    {
        return $this->hasMany(Construction::class);
    }
}
