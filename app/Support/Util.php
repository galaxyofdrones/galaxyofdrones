<?php

namespace Koodilab\Support;

class Util
{
    /**
     * Set a flash message.
     *
     * @param string $message
     * @param string $title
     *
     * @return FlashManager|null
     */
    public static function flash($message = null, $title = null)
    {
        $flash = app(FlashManager::class);

        if (! $message) {
            return $flash;
        }

        return $flash->info($message, $title);
    }

    /**
     * Get the gravatar.
     *
     * @param string $email
     * @param array  $parameters
     *
     * @return string
     */
    public static function gravatar($email = null, array $parameters = [])
    {
        $hash = '';

        if (! empty($email)) {
            $hash = md5(mb_strtolower($email));
        }

        $query = '';

        if (! empty($parameters)) {
            $query = '?'.http_build_query($parameters);
        }

        return "//gravatar.com/avatar/{$hash}{$query}";
    }

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
     * @throws \InvalidArgumentException
     *
     * @return SettingManager|mixed
     */
    public static function setting($key = null, $locale = null, $fallbackToDefault = true)
    {
        $manager = app(SettingManager::class);

        if (! $key) {
            return $manager;
        }

        return $manager->value($key, $locale, $fallbackToDefault);
    }

    /**
     * Show the vue value.
     *
     * @param string $value
     *
     * @return string
     */
    public static function vue($value)
    {
        return "{{ {$value} }}";
    }
}
