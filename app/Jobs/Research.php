<?php

namespace Koodilab\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Koodilab\Models\Research as ResearchModel;

class Research implements ShouldQueue
{
    use Queueable;

    /**
     * The research id.
     *
     * @var int
     */
    protected $researchId;

    /**
     * Constructor.
     *
     * @param int $researchId
     */
    public function __construct($researchId)
    {
        $this->researchId = $researchId;
    }

    /**
     * Handle the job.
     *
     * @param DatabaseManager $database
     */
    public function handle(DatabaseManager $database)
    {
        $research = ResearchModel::find($this->researchId);

        if ($research) {
            $database->transaction(function () use ($research) {
                $research->finish();
            });
        }
    }
}
