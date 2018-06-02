<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Models\Expedition;

class ExpeditionClearCommand extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'expedition:clear';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Clear the expeditions';

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
            $this->prependTimestamp('Clearing expeditions...')
        );

        $this->database->transaction(function () {
            Expedition::newModelInstance()->deleteAllExpired();
        });

        $this->info(
            $this->prependTimestamp('Clear complete!')
        );
    }
}
