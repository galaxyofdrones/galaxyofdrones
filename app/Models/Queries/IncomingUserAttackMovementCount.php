<?php

namespace App\Models\Queries;

use App\Models\Movement;
use Illuminate\Database\Eloquent\Builder;

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
