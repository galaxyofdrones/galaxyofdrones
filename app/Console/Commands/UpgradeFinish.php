<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Game\UpgradeManager;
use Koodilab\Models\Upgrade;
use Symfony\Component\Console\Input\InputArgument;

class UpgradeFinish extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'finish:upgrade';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Finish the upgrade';

    /**
     * The database manager instance.
     *
     * @var DatabaseManager
     */
    protected $database;

    /**
     * The upgrade manager instance.
     *
     * @var UpgradeManager
     */
    protected $manager;

    /**
     * Constructor.
     *
     * @param DatabaseManager $database
     * @param UpgradeManager  $manager
     */
    public function __construct(DatabaseManager $database, UpgradeManager $manager)
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
            $ids = Upgrade::pluck('id');
        }

        $this->database->transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $this->finishUpgrade($id);
            }
        });
    }

    /**
     * Finish the upgrade.
     *
     * @param int $id
     */
    protected function finishUpgrade($id)
    {
        /** @var Upgrade $upgrade */
        $upgrade = Upgrade::find($id);

        if ($upgrade) {
            $this->manager->finish($upgrade);

            $this->info(
                $this->prependTimestamp("The upgrade [{$id}] has been finished!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The upgrade [{$id}] not found.")
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
            ['id', InputArgument::IS_ARRAY, 'The ID of the upgrade'],
        ];
    }
}
