<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Mission;

trait HasManyMission
{
    /**
     * Get the missions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}
