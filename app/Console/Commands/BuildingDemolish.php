<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Models\Grid;

class BuildingDemolish extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'building:demolish {grid}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Demolish a building';

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
        /** @var Grid $grid */
        $grid = Grid::find($this->argument('grid'));

        if (! $grid) {
            $this->error(
                $this->prependTimestamp("The grid [{$grid->id}] not found.")
            );
        } else {
            $this->database->transaction(function () use ($grid) {
                $grid->demolishBuilding();

                $this->info(
                    $this->prependTimestamp("The building [{$grid->id}] has been demolished!")
                );
            });
        }
    }
}
