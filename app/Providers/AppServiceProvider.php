<?php

namespace Koodilab\Providers;

use Illuminate\Support\ServiceProvider;
use Koodilab\Battle\Simulator;
use Koodilab\Contracts\Battle\Simulator as SimulatorContract;
use Koodilab\Contracts\Starmap\Generator as GeneratorContract;
use Koodilab\Contracts\Starmap\NameGenerator as NameGeneratorContract;
use Koodilab\Contracts\Starmap\Renderer as RendererContract;
use Koodilab\Game\ConstructionManager;
use Koodilab\Game\StateManager;
use Koodilab\Starmap\Generator;
use Koodilab\Starmap\NameGenerator;
use Koodilab\Starmap\Renderer;
use Koodilab\Support\FlashManager;
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
        $this->app->singleton(ConstructionManager::class);
        $this->app->singleton(FlashManager::class);
        $this->app->singleton(GeneratorContract::class, Generator::class);
        $this->app->singleton(NameGeneratorContract::class, NameGenerator::class);
        $this->app->singleton(RendererContract::class, Renderer::class);
        $this->app->singleton(SettingManager::class);
        $this->app->singleton(SimulatorContract::class, Simulator::class);
        $this->app->singleton(StateManager::class);
    }
}
