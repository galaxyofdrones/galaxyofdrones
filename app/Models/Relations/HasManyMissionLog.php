<?php

namespace App\Models\Relations;

use App\Models\MissionLog;

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
