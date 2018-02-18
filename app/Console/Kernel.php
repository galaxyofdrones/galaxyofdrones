<?php

namespace Koodilab\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * {@inheritdoc}
     */
    protected $commands = [
        Commands\BuildingDemolish::class,
        Commands\ConstructionFinish::class,
        Commands\MissionClearCommand::class,
        Commands\MissionGenerateCommand::class,
        Commands\MovementFinish::class,
        Commands\PlanetOccupyCommand::class,
        Commands\ResearchFinish::class,
        Commands\StarmapGenerateCommand::class,
        Commands\StarmapRenderCommand::class,
        Commands\TrainingFinish::class,
        Commands\UpgradeFinish::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function bootstrap()
    {
        parent::bootstrap();

        if ($this->app->environment() != 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('mission:generate')->cron('0 */6 * * *');
    }

    /**
     * {@inheritdoc}
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
