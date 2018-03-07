<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Game\MovementManager;
use Koodilab\Models\Movement;
use Symfony\Component\Console\Input\InputArgument;

class MovementFinish extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'finish:movement';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Finish the movement';

    /**
     * The database manager instance.
     *
     * @var DatabaseManager
     */
    protected $database;

    /**
     * The movement manager instance.
     *
     * @var MovementManager
     */
    protected $manager;

    /**
     * Constructor.
     *
     * @param DatabaseManager $database
     * @param MovementManager $manager
     */
    public function __construct(DatabaseManager $database, MovementManager $manager)
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
            $ids = Movement::pluck('id');
        }

        $this->database->transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $this->finishMovement($id);
            }
        });
    }

    /**
     * Finish the movement.
     *
     * @param int $id
     */
    protected function finishMovement($id)
    {
        /** @var Movement $movement */
        $movement = Movement::find($id);

        if ($movement) {
            $this->manager->finish($movement);

            $this->info(
                $this->prependTimestamp("The movement [{$id}] has been finished!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The movement [{$id}] not found.")
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::IS_ARRAY, 'The ID of the movement'],
        ];
    }
}
