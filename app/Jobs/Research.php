<?php

namespace Koodilab\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Koodilab\Game\ResearchManager;
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
     * @param ResearchManager $manager
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
