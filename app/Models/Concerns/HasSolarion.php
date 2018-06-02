<?php

namespace Koodilab\Models\Concerns;

trait HasSolarion
{
    /**
     * Has solarion?
     *
     * @param int $solarion
     *
     * @return bool
     */
    public function hasSolarion($solarion)
    {
        return $this->solarion >= $solarion;
    }

    /**
     * Increment the solarion.
     *
     * @param int $amount
     */
    public function incrementSolarion($amount)
    {
        $this->update([
            'solarion' => $this->solarion + $amount,
        ]);
    }

    /**
     * Decrement the solarion.
     *
     * @param int $amount
     */
    public function decrementSolarion($amount)
    {
        $this->update([
            'solarion' => max(0, $this->solarion - $amount),
        ]);
    }
}
