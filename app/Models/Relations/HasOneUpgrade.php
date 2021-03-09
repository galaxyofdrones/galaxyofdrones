<?php

namespace App\Models\Relations;

use App\Models\Upgrade;

trait HasOneUpgrade
{
    /**
     * Get the upgrade.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function upgrade()
    {
        return $this->hasOne(Upgrade::class);
    }
}
