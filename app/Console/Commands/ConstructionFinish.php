<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Game\ConstructionManager;
use Koodilab\Models\Construction;
use Symfony\Component\Console\Input\InputArgument;

class ConstructionFinish extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'finish:construction';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Finish the construction';

    /**
     * The database manager instance.
     *
     * @var DatabaseManager
     */
    protected $database;

    /**
     * The construction manager instance.
     *
     * @var ConstructionManager
     */
    protected $manager;

    /**
     * Constructor.
     *
     * @param DatabaseManager     $database
     * @param ConstructionManager $manager
     */
    public function __construct(DatabaseManager $database, ConstructionManager $manager)
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
        $ids = $this->argument('id');

        if (count($ids) === 1 && $ids[0] === 'all') {
            $ids = Construction::pluck('id');
        }

        $this->database->transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $this->finishConstruction($id);
            }
        });
    }

    /**
     * Finish the construction.
     *
     * @param int $id
     */
    protected function finishConstruction($id)
    {
        /** @var Construction $construction */
        $construction = Construction::find($id);

        if ($construction) {
            $this->manager->finish($construction);

            $this->info(
                $this->prependTimestamp("The construction [{$id}] has been finished!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The construction [{$id}] not found.")
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::IS_ARRAY, 'The ID of the construction'],
        ];
    }
}
