<?php

namespace App\Models\Queries;

use App\Models\Movement;

trait IncomingCapitalMovementCount
{
    /**
     * Get the incoming capital movement count.
     *
     * @return int
     */
    public function incomingCapitalMovementCount()
    {
        return $this->incomingMovements()
            ->whereIn('type', [
                Movement::TYPE_TRADE, Movement::TYPE_PATROL,
            ])->count();
    }
}
