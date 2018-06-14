<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\User;

trait FindByBlocked
{
    /**
     * Find by blocked.
     *
     * @param User  $blocked
     * @param array $columns
     *
     * @return \Koodilab\Models\Block
     */
    public function findByBlocked(User $blocked, $columns = ['*'])
    {
        return $this->blocks()
            ->where('blocked_id', $blocked->id)
            ->first($columns);
    }
}
