<?php

namespace App\Models\Relations;

use App\Models\ExpeditionLog;

trait HasManyExpeditionLog
{
    /**
     * Get the expedition logs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expeditionLogs()
    {
        return $this->hasMany(ExpeditionLog::class);
    }
}
