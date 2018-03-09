<?php

namespace Koodilab\Models\Concerns;

use Carbon\Carbon;

trait HasExperience
{
    /**
     * Get the level attribute.
     *
     * @return int
     */
    public function getLevelAttribute()
    {
        return (int) ($this->experienceOffset() * sqrt($this->experience)) + 1;
    }

    /**
     * Get the level expereience attribute.
     *
     * @return int
     */
    public function getLevelExperienceAttribute()
    {
        return (int) pow(($this->level - 1) / $this->experienceOffset(), 2);
    }

    /**
     * Get the next level attribute.
     *
     * @return int
     */
    public function getNextLevelAttribute()
    {
        return $this->level + 1;
    }

    /**
     * Get the next level expereience attribute.
     *
     * @return int
     */
    public function getNextLevelExperienceAttribute()
    {
        return (int) pow($this->level / $this->experienceOffset(), 2);
    }

    /**
     * Increment the experience.
     *
     * @param int $amount
     */
    public function incrementExperience($amount)
    {
        $this->update([
            'experience' => $this->experience + $amount,
        ]);
    }

    /**
     * Increment the energy and experience.
     *
     * @param int $energyAmount
     * @param int $experienceAmount
     */
    public function incrementEnergyAndExperience($energyAmount, $experienceAmount)
    {
        $this->update([
            'energy' => $this->energy + $energyAmount,
            'experience' => $this->experience + $experienceAmount,
            'last_energy_changed' => Carbon::now(),
        ]);
    }

    /**
     * Increment the solarion and experience.
     *
     * @param int $solarionAmount
     * @param int $experienceAmount
     */
    public function incrementSolarionAndExperience($solarionAmount, $experienceAmount)
    {
        $this->update([
            'solarion' => $this->solarion + $solarionAmount,
            'experience' => $this->experience + $experienceAmount,
        ]);
    }

    /**
     * Get the experience offset.
     *
     * @return float
     */
    protected function experienceOffset()
    {
        return 0.04;
    }
}
