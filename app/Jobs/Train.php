<?php

namespace Koodilab\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Koodilab\Game\TrainingManager;
use Koodilab\Models\Training as TrainingModel;

class Train implements ShouldQueue
{
    use Queueable;

    /**
     * The training id.
     *
     * @var int
     */
    protected $trainingId;

    /**
     * Constructor.
     *
     * @param int $trainingId
     */
    public function __construct($trainingId)
    {
        $this->trainingId = $trainingId;
    }

    /**
     * Handle the job.
     *
     * @param DatabaseManager $database
     * @param TrainingManager $manager
     */
    public function handle(DatabaseManager $database, TrainingManager $manager)
    {
        $training = TrainingModel::find($this->trainingId);

        if ($training) {
            $database->transaction(function () use ($training, $manager) {
                $manager->finish($training);
            });
        }
    }
}
