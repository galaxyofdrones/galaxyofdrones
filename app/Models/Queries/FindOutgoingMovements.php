<?php

namespace App\Models\Queries;

trait FindOutgoingMovements
{
    /**
     * Find outgoing movements.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Movement[]
     */
    public function findOutgoingMovements($columns = ['*'])
    {
        return $this->outgoingMovements()
            ->with('start', 'end')
            ->where('user_id', $this->user_id)
            ->get($columns);
    }
}
