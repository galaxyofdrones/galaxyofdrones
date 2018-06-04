<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Shield;

trait HasOneShield
{
    /**
     * Get the shield.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shield()
    {
        return $this->hasOne(Shield::class);
    }
}
