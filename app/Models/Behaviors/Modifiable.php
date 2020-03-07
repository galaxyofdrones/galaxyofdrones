<?php

namespace Koodilab\Models\Behaviors;

trait Modifiable
{
    /**
     * The modifiers.
     *
     * @var array
     */
    protected $modifiers = [];

    /**
     * Apply the modifiers.
     *
     * @return static
     */
    public function applyModifiers(array $modifiers)
    {
        if ($this->validateModifiers($modifiers)) {
            $this->modifiers = array_merge($this->modifiers, $modifiers);
        }

        return $this;
    }

    /**
     * Validate the modifiers.
     *
     * @return bool
     */
    protected function validateModifiers(array $modifiers)
    {
        return true;
    }
}
