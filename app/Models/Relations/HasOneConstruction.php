<?php

namespace App\Models\Relations;

use App\Models\Construction;

trait HasOneConstruction
{
    /**
     * Get the construction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function construction()
    {
        return $this->hasOne(Construction::class);
    }
}
