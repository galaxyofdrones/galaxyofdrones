<?php

namespace Koodilab\Models\Queries;

use Illuminate\Database\Eloquent\Builder;
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
        return Movement::whereHas('end', function (Builder $query) {
            $query->where('user_id', $this->id);
        })->whereIn('type', [
            Movement::TYPE_ATTACK, Movement::TYPE_OCCUPY,
        ])->count();
    }
}
