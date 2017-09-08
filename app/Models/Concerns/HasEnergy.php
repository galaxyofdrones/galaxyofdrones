<?php

namespace Koodilab\Models\Concerns;

use Carbon\Carbon;

trait HasEnergy
{
    /**
     * The "booting" method of the trait.
     */
    public static function bootHasEnergy()
    {
        static::saving(function ($model) {
            if ($model->isDirty(['energy', 'production_rate'])) {
                $model->last_energy_changed = Carbon::now();
            }
        });
    }

    /**
     * Get the energy attribute.
     *
     * @return int
     */
    public function getEnergyAttribute()
    {
        $energy = 0;

        if (!empty($this->attributes['energy'])) {
            $energy = $this->attributes['energy'];
        }

        $produced = round(
            $this->production_rate / 3600 * Carbon::now()->diffInSeconds($this->last_energy_changed)
        );

        return $energy + $produced;
    }

    /**
     * Has energy?
     *
     * @param int $energy
     *
     * @return bool
     */
    public function hasEnergy($energy)
    {
        return $this->energy >= $energy;
    }

    /**
     * Increment the energy.
     *
     * @param int $amount
     */
    public function incrementEnergy($amount)
    {
        $this->update([
            'energy' => $this->energy + $amount,
        ]);
    }

    /**
     * Decrement the energy.
     *
     * @param int $amount
     */
    public function decrementEnergy($amount)
    {
        $this->update([
            'energy' => max(0, $this->energy - $amount),
        ]);
    }
}
