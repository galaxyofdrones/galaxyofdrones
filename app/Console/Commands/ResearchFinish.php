<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Models\Research;
use Symfony\Component\Console\Input\InputArgument;

class ResearchFinish extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'research:finish';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Finish the research';

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
        $ids = $this->argument('id');

        if (count($ids) === 1 && $ids[0] === 'all') {
            $ids = Research::pluck('id');
        }

        $this->database->transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $this->finishResearch($id);
            }
        });
    }

    /**
     * Finish the research.
     *
     * @param int $id
     */
    protected function finishResearch($id)
    {
        /** @var Research $research */
        $research = Research::find($id);

        if ($research) {
            $research->finish();

            $this->info(
                $this->prependTimestamp("The research [{$id}] has been finished!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The research [{$id}] not found.")
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
            ['id', InputArgument::IS_ARRAY, 'The ID of the research'],
        ];
    }
}
