<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Models\Mission;

class MissionClearCommand extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'mission:clear';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Clear the missions';

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
        $this->info(
            $this->prependTimestamp('Clearing missions...')
        );

        $this->database->transaction(function () {
            Mission::newModelInstance()->deleteAllExpired();
        });

        $this->info(
            $this->prependTimestamp('Clear complete!')
        );
    }
}
