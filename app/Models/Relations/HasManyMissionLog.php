<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\MissionLog;

trait HasManyMissionLog
{
    /**
     * Get the mission logs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function missionLogs()
    {
        return $this->hasMany(MissionLog::class);
    }
}
