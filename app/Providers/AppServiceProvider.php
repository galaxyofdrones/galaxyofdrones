<?php

namespace Koodilab\Providers;

use Illuminate\Support\ServiceProvider;
use Koodilab\Support\SettingManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(SettingManager::class);
    }
}
