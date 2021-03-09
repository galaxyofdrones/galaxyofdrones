<?php

namespace App\Jobs;

use App\Game\TrainingManager;
use App\Models\Training as TrainingModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;

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
     * @throws \Exception|\Throwable
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
