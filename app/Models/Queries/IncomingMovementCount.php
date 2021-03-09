<?php

namespace App\Models\Queries;

use App\Models\Movement;

trait IncomingMovementCount
{
    /**
     * Get the incoming movement count.
     *
     * @return int
     */
    public function incomingMovementCount()
    {
        return $this->incomingMovements()
            ->whereNotIn('type', [
                Movement::TYPE_SCOUT,
            ])->count();
    }
}
