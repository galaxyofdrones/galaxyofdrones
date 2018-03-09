<?php

namespace Koodilab\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * {@inheritdoc}
     */
    protected $commands = [];

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
        $schedule->command('expedition:generate')->cron('0 */6 * * *');
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
