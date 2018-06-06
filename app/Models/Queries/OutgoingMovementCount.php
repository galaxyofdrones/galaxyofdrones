<?php

namespace Koodilab\Models\Queries;

trait OutgoingMovementCount
{
    /**
     * Get the outgoing movement count.
     *
     * @return int
     */
    public function outgoingMovementCount()
    {
        return $this->outgoingMovements()
            ->where('user_id', $this->user_id)
            ->count();
    }
}
