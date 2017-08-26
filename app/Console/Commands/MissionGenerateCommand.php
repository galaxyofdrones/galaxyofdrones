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
     * {@inheritdoc}
     */
    public function handle()
    {
        $this->call('mission:clear');

        $this->info(
            $this->prependTimestamp('Generating missions...')
        );

        $this->database->transaction(function () {
            /** @var Building $building */
            $building = Building::where('type', Building::TYPE_TRADER)
                ->first(['id', 'type', 'end_level', 'mission_time']);

            if ($building) {
                $grids = Grid::where('building_id', $building->id)->get(['id', 'planet_id', 'level']);

                foreach ($grids as $grid) {
                    $building->applyModifiers([
                        'level' => $grid->level,
                    ]);

                    /** @var \Koodilab\Models\Planet $planet */
                    $planet = $grid->planet()->first(['id', 'user_id', 'capacity']);

                    /* @var \Koodilab\Models\User $user */
                    $user = $planet->user()->first(['id']);

                    /** @var \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Resource[] $resources */
                    $resources = $user->resources()
                        ->orderBy('efficiency')
                        ->get(['resources.id', 'frequency', 'efficiency']);

                    Mission::createRand($planet, $building, $resources);
                }
            }
        });

        $this->info(
            $this->prependTimestamp('Generation complete!')
        );
    }
}
