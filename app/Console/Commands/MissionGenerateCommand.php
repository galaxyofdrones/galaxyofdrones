<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Models\Mission;
use Koodilab\Models\User;

class MissionGenerateCommand extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'mission:generate';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Generate the missions';

    /**
     * The database manager instance.
     *
     * @var DatabaseManager
     */
    protected $database;

    /**
     * Constructor.
     *
     * @param DatabaseManager $database
     */
    public function __construct(DatabaseManager $database)
    {
        parent::__construct();

        $this->database = $database;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('mission:clear');

        $this->info(
            $this->prependTimestamp('Generating missions...')
        );

        $this->database->transaction(function () {
            $users = User::whereNotNull('started_at')->get();

            foreach ($users as $user) {
                Mission::createRand($user);
            }
        });

        $this->info(
            $this->prependTimestamp('Generation complete!')
        );
    }
}
