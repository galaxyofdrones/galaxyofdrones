<?php

namespace Koodilab\Providers;

use Illuminate\Support\ServiceProvider;
use Koodilab\Battle\Simulator;
use Koodilab\Contracts\Battle\Simulator as SimulatorContract;
use Koodilab\Contracts\Starmap\Generator as GeneratorContract;
use Koodilab\Contracts\Starmap\NameGenerator as NameGeneratorContract;
use Koodilab\Contracts\Starmap\Renderer as RendererContract;
use Koodilab\Game\BattleManager;
use Koodilab\Game\ConstructionManager;
use Koodilab\Game\ExpeditionManager;
use Koodilab\Game\MissionManager;
use Koodilab\Game\MovementManager;
use Koodilab\Game\ResearchManager;
use Koodilab\Game\ShieldManager;
use Koodilab\Game\StateManager;
use Koodilab\Game\StorageManager;
use Koodilab\Game\TrainingManager;
use Koodilab\Game\UpgradeManager;
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
        $this->app->singleton(GeneratorContract::class, Generator::class);
        $this->app->singleton(NameGeneratorContract::class, NameGenerator::class);
        $this->app->singleton(RendererContract::class, Renderer::class);
        $this->app->singleton(SimulatorContract::class, Simulator::class);

        $this->app->singleton(BattleManager::class);
        $this->app->singleton(ConstructionManager::class);
        $this->app->singleton(ExpeditionManager::class);
        $this->app->singleton(FlashManager::class);
        $this->app->singleton(MissionManager::class);
        $this->app->singleton(MovementManager::class);
        $this->app->singleton(ResearchManager::class);
        $this->app->singleton(SettingManager::class);
        $this->app->singleton(ShieldManager::class);
        $this->app->singleton(StateManager::class);
        $this->app->singleton(StorageManager::class);
        $this->app->singleton(TrainingManager::class);
        $this->app->singleton(UpgradeManager::class);
    }
}
