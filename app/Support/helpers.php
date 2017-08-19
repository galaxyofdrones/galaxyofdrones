<?php

use Koodilab\Support\Util;

if (!function_exists('setting')) {
    /**
     * Get the setting.
     *
     * @param string $key
     * @param string $locale
     * @param bool   $fallbackToDefault
     *
     * @return \Koodilab\Support\SettingManager|mixed
     *
     * @throws InvalidArgumentException
     */
    function setting($key = null, $locale = null, $fallbackToDefault = true)
    {
        return Util::setting($key, $locale, $fallbackToDefault);
    }
}
