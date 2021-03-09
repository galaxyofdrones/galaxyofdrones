<?php

namespace App\Models\Queries;

use App\Models\Movement;

trait FindIncomingMovements
{
    /**
     * Find incoming movements.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Movement[]
     */
    public function findIncomingMovements($columns = ['*'])
    {
        return $this->incomingMovements()
            ->with('start', 'end')
            ->whereNotIn('type', [
                Movement::TYPE_SCOUT,
            ])->get($columns);
    }
}
