<?php

namespace Koodilab\Models\Concerns;

trait HasCustomName
{
    /**
     * Get the display name attribute.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->custom_name ?: $this->name;
    }

    /**
     * Set the custom name attribute.
     *
     * @param string $value
     */
    public function setCustomNameAttribute($value)
    {
        $this->attributes['custom_name'] = $this->name != $value
            ? $value
            : null;
    }
}
