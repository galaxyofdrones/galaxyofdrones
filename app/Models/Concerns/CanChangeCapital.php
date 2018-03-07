<?php

namespace Koodilab\Models\Concerns;

use Carbon\Carbon;

trait CanChangeCapital
{
    /**
     * Can change capital?
     *
     * @return bool
     */
    public function canChangeCapital()
    {
        if (! $this->last_capital_changed) {
            return false;
        }

        return $this->last_capital_changed->copy()
            ->addSeconds($this->capitalChangeCooldown())
            ->lte(Carbon::now());
    }

    /**
     * Get the capital change remaining attribute.
     *
     * @return int
     */
    public function getCapitalChangeRemainingAttribute()
    {
        if ($this->canChangeCapital()) {
            return null;
        }

        return Carbon::now()->diffInSeconds(
            $this->last_capital_changed->copy()->addSeconds($this->capitalChangeCooldown())
        );
    }

    /**
     * Get the capital change cooldown.
     *
     * @return int
     */
    protected function capitalChangeCooldown()
    {
        return 86400;
    }
}
