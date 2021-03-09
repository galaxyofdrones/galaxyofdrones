<?php

namespace App\Providers;

use App\Battle\Simulator;
use App\Contracts\Battle\Simulator as SimulatorContract;
use App\Contracts\Starmap\Generator as GeneratorContract;
use App\Contracts\Starmap\NameGenerator as NameGeneratorContract;
use App\Contracts\Starmap\Renderer as RendererContract;
use App\Game\BattleManager;
use App\Game\ConstructionManager;
use App\Game\ExpeditionManager;
use App\Game\MissionManager;
use App\Game\MovementManager;
use App\Game\ResearchManager;
use App\Game\ShieldManager;
use App\Game\StateManager;
use App\Game\StorageManager;
use App\Game\TrainingManager;
use App\Game\UpgradeManager;
use App\Starmap\Generator;
use App\Starmap\NameGenerator;
use App\Starmap\Renderer;
use App\Support\FlashManager;
use App\Support\SettingManager;
use Illuminate\Support\ServiceProvider;

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
