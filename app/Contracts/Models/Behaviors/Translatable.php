<?php

namespace Koodilab\Contracts\Models\Behaviors;

interface Translatable
{
    /**
     * Get the translation.
     *
     * @param string $key
     * @param string $locale
     * @param bool   $fallbackToDefault
     *
     * @return string
     */
    public function translation($key, $locale = null, $fallbackToDefault = true);

    /**
     * Set the translation.
     *
     * @param string $key
     * @param string $value
     * @param string $locale
     *
     * @return static
     */
    public function setTranslation($key, $value, $locale = null);

    /**
     * Get the locale.
     *
     * @return string
     */
    public function locale();

    /**
     * Set the locale.
     *
     * @param $locale
     *
     * @return static
     */
    public function setLocale($locale);

    /**
     * Get the fallback locale.
     *
     * @return string
     */
    public function fallbackLocale();
}
