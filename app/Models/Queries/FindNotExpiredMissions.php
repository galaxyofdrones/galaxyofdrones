<?php

namespace Koodilab\Models\Queries;

use Carbon\Carbon;

trait FindNotExpiredMissions
{
    /**
     * Find not expired missions.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Mission[]
     */
    public function findNotExpiredMissions($columns = ['*'])
    {
        return $this->missions()
            ->where('ended_at', '>=', Carbon::now())
            ->orderBy('ended_at')
            ->get($columns);
    }
}
