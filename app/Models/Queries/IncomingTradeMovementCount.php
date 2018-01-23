<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Movement;

trait IncomingTradeMovementCount
{
    /**
     * Get the incoming trade movement count.
     *
     * @return int
     */
    public function incomingTradeMovementCount()
    {
        return $this->incomingMovements()
            ->where('type', Movement::TYPE_TRADE)
            ->count();
    }
}
