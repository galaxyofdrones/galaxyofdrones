<?php

namespace Koodilab\Support;

class Util
{
    /**
     * Get a random float.
     *
     * @return float
     */
    public static function randFloat()
    {
        return (float) mt_rand() / (float) mt_getrandmax();
    }

    /**
     * Get the setting.
     *
     * @param string $key
     * @param string $locale
     * @param bool   $fallbackToDefault
     *
     * @return SettingManager|mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function setting($key = null, $locale = null, $fallbackToDefault = true)
    {
        $manager = app(SettingManager::class);

        if (!$key) {
            return $manager;
        }

        return $manager->value($key, $locale, $fallbackToDefault);
    }
}
