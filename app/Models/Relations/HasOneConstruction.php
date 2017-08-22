<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Construction;

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
