<?php

namespace Koodilab\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Koodilab\Game\UpgradeManager;
use Koodilab\Models\Upgrade as UpgradeModel;

class Upgrade implements ShouldQueue
{
    use Queueable;

    /**
     * The upgrade id.
     *
     * @var int
     */
    protected $upgradeId;

    /**
     * Constructor.
     *
     * @param int $upgradeId
     */
    public function __construct($upgradeId)
    {
        $this->upgradeId = $upgradeId;
    }

    /**
     * Handle the job.
     *
     * @param DatabaseManager $database
     * @param UpgradeManager  $manager
     */
    public function handle(DatabaseManager $database, UpgradeManager $manager)
    {
        $upgrade = UpgradeModel::find($this->upgradeId);

        if ($upgrade) {
            $database->transaction(function () use ($upgrade, $manager) {
                $manager->finish($upgrade);
            });
        }
    }
}
