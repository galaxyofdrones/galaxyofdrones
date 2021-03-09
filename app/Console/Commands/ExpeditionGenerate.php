<?php

namespace App\Console\Commands;

use App\Console\Behaviors\PrependTimestamp;
use App\Game\ExpeditionManager;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;

class ExpeditionGenerate extends Command
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
     */
    public function __construct(DatabaseManager $database, ExpeditionManager $manager)
    {
        parent::__construct();

        $this->database = $database;
        $this->manager = $manager;
    }

    /**
     * Execute the console command.
     *
     * @throws \Exception|\Throwable
     */
    public function handle()
    {
        $this->call('expedition:clear');

        $this->info(
            $this->prependTimestamp('Generating expeditions...')
        );

        $users = User::whereNotNull('started_at')->get();

        foreach ($users as $user) {
            $this->database->transaction(function () use ($user) {
                $this->manager->createRand($user);
            });
        }

        $this->info(
            $this->prependTimestamp('Generation complete!')
        );
    }
}
