<?php

namespace App\Models\Queries;

use App\Models\Movement;

trait IncomingAttackMovementCount
{
    /**
     * Get the incoming attack movement count.
     *
     * @return int
     */
    public function incomingAttackMovementCount()
    {
        return $this->incomingMovements()
            ->whereIn('type', [
                Movement::TYPE_ATTACK, Movement::TYPE_OCCUPY,
            ])->count();
    }
}
