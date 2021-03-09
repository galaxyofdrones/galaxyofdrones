<?php

namespace App\Jobs;

use App\Game\ResearchManager;
use App\Models\Research as ResearchModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;

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
     * @throws \Exception|\Throwable
     */
    public function handle(DatabaseManager $database, ResearchManager $manager)
    {
        $research = ResearchModel::find($this->researchId);

        if ($research) {
            $database->transaction(function () use ($research, $manager) {
                $manager->finish($research);
            });
        }
    }
}
