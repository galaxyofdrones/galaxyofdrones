<?php

namespace App\Models\Concerns;

use Carbon\Carbon;

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

    /**
     * Decrement the energy and solarion.
     *
     * @param int $energyAmount
     * @param int $solarionAmount
     */
    public function decrementEnergyAndSolarion($energyAmount, $solarionAmount)
    {
        $this->update([
            'energy' => max(0, $this->energy - $energyAmount),
            'last_energy_changed' => Carbon::now(),
            'solarion' => max(0, $this->solarion - $solarionAmount),
        ]);
    }
}
