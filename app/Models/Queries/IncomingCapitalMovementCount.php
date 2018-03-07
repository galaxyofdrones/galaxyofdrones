<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Movement;

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
