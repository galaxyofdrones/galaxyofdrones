<?php

namespace Koodilab\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     */
    public function handle(DatabaseManager $database)
    {
        $movement = MovementModel::find($this->movementId);

        if ($movement) {
            $database->transaction(function () use ($movement) {
                $movement->finish();
            });
        }
    }
}
