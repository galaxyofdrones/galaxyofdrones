<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Movement;

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
