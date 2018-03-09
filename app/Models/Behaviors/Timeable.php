<?php

namespace Koodilab\Models\Behaviors;

use Carbon\Carbon;

trait Timeable
{
    /**
     * Get the remaining attribute.
     *
     * @return int
     */
    public function getRemainingAttribute()
    {
        return max(0, Carbon::now()->diffInSeconds(
            $this->getAttribute($this->endedAtKey()),
            false
        ));
    }

    /**
     * Delete the expired.
     *
     * @return bool|null
     */
    public function deleteExpired()
    {
        return static::where($this->endedAtKey(), '<', Carbon::now())->delete();
    }

    /**
     * Get the ended at key.
     *
     * @return string
     */
    protected function endedAtKey()
    {
        return 'ended_at';
    }
}
