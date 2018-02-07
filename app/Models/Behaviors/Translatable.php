<?php

namespace Koodilab\Models\Behaviors;

trait Translatable
{
    /**
     * The locale.
     *
     * @var string
     */
    protected $locale;

    /**
     * Get the translation.
     *
     * @param string $key
     * @param string $locale
     * @param bool   $fallbackToDefault
     *
     * @return string
     */
    public function translation($key, $locale = null, $fallbackToDefault = true)
    {
        $locale = $locale ?: $this->locale();
        $translations = $this->{$key};

        if (! empty($translations[$locale])) {
            return $translations[$locale];
        }

        if ($fallbackToDefault) {
            $locale = $this->fallbackLocale();

            if (! empty($translations[$locale])) {
                return $translations[$locale];
            }
        }
    }

    /**
     * Set the translation.
     *
     * @param string $key
     * @param string $value
     * @param string $locale
     *
     * @return static
     */
    public function setTranslation($key, $value, $locale = null)
    {
        $locale = $locale ?: $this->locale();
        $translations = $this->{$key};

        if (! array_key_exists($locale, $translations) || $translations[$locale] != $value) {
            $this->{$key} = array_merge($translations, [
                $locale => $value,
            ]);
        }

        return $this;
    }

    /**
     * Get the locale.
     *
     * @return string
     */
    public function locale()
    {
        return $this->locale ?: config('app.locale');
    }

    /**
     * Set the locale.
     *
     * @param $locale
     *
     * @return static
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get the fallback locale.
     *
     * @return string
     */
    public function fallbackLocale()
    {
        return config('app.fallback_locale');
    }
}
