<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Movement;

trait IncomingUserAttackMovementCount
{
    /**
     * Get the incoming user attack movement count.
     *
     * @return int
     */
    public function incomingUserAttackMovementCount()
    {
        return Movement::join('planets', 'movements.end_id', '=', 'planets.id')
            ->where('planets.user_id', $this->id)
            ->whereIn('type', [
                Movement::TYPE_ATTACK, Movement::TYPE_OCCUPY,
            ])->count();
    }
}
