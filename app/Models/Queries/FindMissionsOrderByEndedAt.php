<?php

namespace Koodilab\Models\Queries;

trait FindMissionsOrderByEndedAt
{
    /**
     * Find missions order by ended at.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Mission[]
     */
    public function findMissionsOrderByEndedAt($columns = ['*'])
    {
        return $this->missions()
            ->orderBy('ended_at')
            ->get($columns);
    }
}
