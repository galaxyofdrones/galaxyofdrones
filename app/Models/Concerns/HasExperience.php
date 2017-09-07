<?php

namespace Koodilab\Models\Concerns;

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
     * Get the experience offset.
     *
     * @return float
     */
    protected function experienceOffset()
    {
        return 0.04;
    }
}
