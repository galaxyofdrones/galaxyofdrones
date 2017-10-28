<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Mission;

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
            /** @var Building $building */
            $building = Building::findByType(Building::TYPE_TRADER);

            if ($building) {
                $grids = Grid::findAllByBuilding($building);

                foreach ($grids as $grid) {
                    $building->applyModifiers([
                        'level' => $grid->level,
                    ]);

                    Mission::createRand(
                        $grid->planet, $building, $grid->planet->user->findResourcesOrderBySortOrder()
                    );
                }
            }
        });

        $this->info(
            $this->prependTimestamp('Generation complete!')
        );
    }
}
