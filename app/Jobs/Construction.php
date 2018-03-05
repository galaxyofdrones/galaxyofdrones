<?php

namespace Koodilab\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Koodilab\Game\ConstructionManager;
use Koodilab\Models\Construction as ConstructionModel;

class Construction implements ShouldQueue
{
    use Queueable;

    /**
     * The construction id.
     *
     * @var int
     */
    protected $constructionId;

    /**
     * Constructor.
     *
     * @param int $constructionId
     */
    public function __construct($constructionId)
    {
        $this->constructionId = $constructionId;
    }

    /**
     * Handle the job.
     *
     * @param DatabaseManager     $database
     * @param ConstructionManager $manager
     */
    public function handle(DatabaseManager $database, ConstructionManager $manager)
    {
        $construction = ConstructionModel::find($this->constructionId);

        if ($construction) {
            $database->transaction(function () use ($manager, $construction) {
                $manager->finish($construction);
            });
        }
    }
}
