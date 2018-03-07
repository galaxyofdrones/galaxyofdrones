<?php

namespace Koodilab\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Koodilab\Game\MovementManager;
use Koodilab\Models\Movement as MovementModel;

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
     * @param DatabaseManager $database
     * @param MovementManager $manager
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
