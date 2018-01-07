<?php

namespace Koodilab\Models\Queries;

trait PaginateMissionLogs
{
    /**
     * Paginate the mission logs.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Koodilab\Models\MissionLog[]
     */
    public function paginateMissionLogs($perPage = 5)
    {
        return $this->missionLogs()
            ->with('resources')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
