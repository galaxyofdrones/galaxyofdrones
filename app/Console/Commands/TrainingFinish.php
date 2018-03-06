<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Game\TrainingManager;
use Koodilab\Models\Training;
use Symfony\Component\Console\Input\InputArgument;

class TrainingFinish extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'finish:training';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Finish the training';

    /**
     * The database manager instance.
     *
     * @var DatabaseManager
     */
    protected $database;

    /**
     * The training manager instance.
     *
     * @var TrainingManager
     */
    protected $manager;

    /**
     * Constructor.
     *
     * @param DatabaseManager $database
     * @param TrainingManager $manager
     */
    public function __construct(DatabaseManager $database, TrainingManager $manager)
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
            $ids = Training::pluck('id');
        }

        $this->database->transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $this->finishTraining($id);
            }
        });
    }

    /**
     * Finish the training.
     *
     * @param int $id
     */
    protected function finishTraining($id)
    {
        /** @var Training $training */
        $training = Training::find($id);

        if ($training) {
            $this->manager->finish($training);

            $this->info(
                $this->prependTimestamp("The training [{$id}] has been finished!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The training [{$id}] not found.")
            );
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::IS_ARRAY, 'The ID of the training'],
        ];
    }
}
