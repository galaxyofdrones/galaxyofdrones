<?php

namespace App\Models\Queries;

use Carbon\Carbon;

trait FindNotExpiredMissions
{
    /**
     * Find not expired missions.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Mission[]
     */
    public function findNotExpiredMissions($columns = ['*'])
    {
        return $this->missions()
            ->with('resources')
            ->where('ended_at', '>=', Carbon::now())
            ->orderBy('ended_at')
            ->get($columns);
    }
}
