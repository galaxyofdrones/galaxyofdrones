<?php

namespace Koodilab\Providers;

use Illuminate\Support\ServiceProvider;
use Koodilab\Battle\Simulator;
use Koodilab\Contracts\Battle\Simulator as SimulatorContract;
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
        $this->app->singleton(SimulatorContract::class, Simulator::class);
        $this->app->singleton(SettingManager::class);
    }
}
