<?php

namespace App\Models\Relations;

use App\Models\Construction;

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
