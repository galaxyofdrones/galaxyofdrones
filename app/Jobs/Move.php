<?php

namespace App\Jobs;

use App\Game\MovementManager;
use App\Models\Movement as MovementModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;

class Move implements ShouldQueue
{
    use Queueable;

    /**
     * The movement id.
     *
     * @var int
     */
    protected $movementId;

    /**
     * Constructor.
     *
     * @param int $movementId
     */
    public function __construct($movementId)
    {
        $this->movementId = $movementId;
    }

    /**
     * Handle the job.
     *
     * @throws \Exception|\Throwable
     */
    public function handle(DatabaseManager $database, MovementManager $manager)
    {
        $movement = MovementModel::find($this->movementId);

        if ($movement) {
            $database->transaction(function () use ($movement, $manager) {
                $manager->finish($movement);
            });
        }
    }
}
