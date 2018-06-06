<?php

namespace Koodilab\Models\Queries;

use Illuminate\Database\Eloquent\Builder;
use Koodilab\Models\Movement;

trait FindIncomingUserAttackMovements
{
    /**
     * Find incoming user attack movements.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Movement[]
     */
    public function findIncomingUserAttackMovements($columns = ['*'])
    {
        return Movement::with('start', 'end')
            ->whereHas('end', function (Builder $query) {
                $query->where('user_id', $this->id);
            })
            ->whereIn('type', [
                Movement::TYPE_ATTACK, Movement::TYPE_OCCUPY,
            ])->get($columns);
    }
}
