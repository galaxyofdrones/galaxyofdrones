<?php

namespace Koodilab\Models\Behaviors;

use Carbon\Carbon;

trait Timeable
{
    /**
     * Is expired?
     *
     * @return bool
     */
    public function isExpired()
    {
        return ! $this->remaining;
    }

    /**
     * Get the remaining attribute.
     *
     * @return int
     */
    public function getRemainingAttribute()
    {
        return max(0, Carbon::now()->diffInSeconds(
            $this->getAttribute($this->endedAtKey()), false
        ));
    }

    /**
     * Delete all expired.
     *
     * @return bool|null
     */
    public function deleteAllExpired()
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
