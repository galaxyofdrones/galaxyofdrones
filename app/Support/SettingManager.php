<?php

namespace Koodilab\Support;

use Illuminate\Contracts\Cache\Repository as Cache;
use InvalidArgumentException;
use Koodilab\Models\Setting;

class SettingManager
{
    /**
     * The cache key.
     *
     * @var string
     */
    const CACHE_KEY = '_setting_manager';

    /**
     * The cache implementation.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get the settings.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Setting[]
     */
    public function all()
    {
        return $this->cache->rememberForever(static::CACHE_KEY, function () {
            return Setting::all()->keyBy('key');
        });
    }

    /**
     * Get the setting value.
     *
     * @param string $key
     * @param string $locale
     * @param bool   $fallbackToDefault
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function value($key, $locale = null, $fallbackToDefault = true)
    {
        $settings = $this->all();

        if (! $settings->has($key)) {
            throw new InvalidArgumentException("There is no key {$key} in the settings table!");
        }

        return $settings->get($key)
            ->translation('value', $locale, $fallbackToDefault);
    }

    /**
     * Forget the cache.
     */
    public function forget()
    {
        $this->cache->forget(static::CACHE_KEY);
    }
}
