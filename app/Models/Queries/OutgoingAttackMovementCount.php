<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Movement;

trait OutgoingAttackMovementCount
{
    /**
     * Get the outgoing attack movement count.
     *
     * @return int
     */
    public function outgoingAttackMovementCount()
    {
        return $this->outgoingMovements()
            ->whereIn('type', [
                Movement::TYPE_SCOUT, Movement::TYPE_ATTACK, Movement::TYPE_OCCUPY,
            ])->count();
    }
}
