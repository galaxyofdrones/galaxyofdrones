<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Game\ExpeditionManager;
use Koodilab\Models\User;

class ExpeditionGenerateCommand extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'expedition:generate';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Generate the expeditions';

    /**
     * The database manager instance.
     *
     * @var DatabaseManager
     */
    protected $database;

    /**
     * The expedition manager instance.
     *
     * @var ExpeditionManager
     */
    protected $manager;

    /**
     * Constructor.
     *
     * @param DatabaseManager   $database
     * @param ExpeditionManager $manager
     */
    public function __construct(DatabaseManager $database, ExpeditionManager $manager)
    {
        parent::__construct();

        $this->database = $database;
        $this->manager = $manager;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('expedition:clear');

        $this->info(
            $this->prependTimestamp('Generating expeditions...')
        );

        $this->database->transaction(function () {
            $users = User::whereNotNull('started_at')->get();

            foreach ($users as $user) {
                $this->manager->createRand($user);
            }
        });

        $this->info(
            $this->prependTimestamp('Generation complete!')
        );
    }
}
