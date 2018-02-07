<?php

namespace Koodilab\Models\Concerns;

trait HasLevel
{
    /**
     * Get the level attribute.
     *
     * @return int
     */
    public function getLevelAttribute()
    {
        if (! empty($this->modifiers['level'])) {
            return $this->modifiers['level'];
        }

        return $this->end_level;
    }

    /**
     * Has the level?
     *
     * @param int $level
     *
     * @return bool
     */
    public function hasLevel($level)
    {
        return $level > 0 && $level <= $this->end_level;
    }

    /**
     * Has lower level?
     *
     * @return bool
     */
    public function hasLowerLevel()
    {
        return $this->level < $this->end_level;
    }

    /**
     * Apply the linear formula.
     *
     * @param mixed $value
     *
     * @return float
     */
    protected function applyLinearForumla($value)
    {
        return $value * ($this->level / $this->end_level);
    }

    /**
     * Apply the exp forumla.
     *
     * @param mixed $value
     * @param int   $exp
     *
     * @return float
     */
    protected function applyExpFormula($value, $exp = 2)
    {
        return $value * pow($this->level / $this->end_level, $exp);
    }
}
