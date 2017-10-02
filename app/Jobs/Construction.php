<?php

namespace Koodilab\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     * @param DatabaseManager $database
     */
    public function handle(DatabaseManager $database)
    {
        $construction = ConstructionModel::find($this->constructionId);

        if ($construction) {
            $database->transaction(function () use ($construction) {
                $construction->finish();
            });
        }
    }
}
