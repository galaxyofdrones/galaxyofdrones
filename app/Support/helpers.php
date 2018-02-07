<?php

use Koodilab\Support\Util;

if (! function_exists('flash')) {
    /**
     * Set a flash message.
     *
     * @param string $message
     * @param string $title
     *
     * @return \Koodilab\Support\FlashManager|null
     */
    function flash($message = null, $title = null)
    {
        return Util::flash($message, $title);
    }
}

if (! function_exists('setting')) {
    /**
     * Get the setting.
     *
     * @param string $key
     * @param string $locale
     * @param bool   $fallbackToDefault
     *
     * @throws InvalidArgumentException
     *
     * @return \Koodilab\Support\SettingManager|mixed
     */
    function setting($key = null, $locale = null, $fallbackToDefault = true)
    {
        return Util::setting($key, $locale, $fallbackToDefault);
    }
}

if (! function_exists('vue')) {
    /**
     * Show the vue value.
     *
     * @param string $value
     *
     * @return string
     */
    function vue($value)
    {
        return Util::vue($value);
    }
}
