<?php

namespace App\Models\Queries;

use App\Models\User;

trait FindByBlocked
{
    /**
     * Find by blocked.
     *
     * @param array $columns
     *
     * @return \App\Models\Block
     */
    public function findByBlocked(User $blocked, $columns = ['*'])
    {
        return $this->blocks()
            ->where('blocked_id', $blocked->id)
            ->first($columns);
    }
}
