<?php

namespace App\Jobs;

use App\Game\ConstructionManager;
use App\Models\Construction as ConstructionModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;

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
     * @throws \Exception|\Throwable
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
