<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Movement;

trait FindIncomingMovements
{
    /**
     * Find incoming movements.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Movement[]
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
