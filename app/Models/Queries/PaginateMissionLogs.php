<?php

namespace Koodilab\Models\Queries;

trait PaginateMissionLogs
{
    /**
     * Paginate the mission logs.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Koodilab\Models\MissionLog[]
     */
    public function paginateMissionLogs()
    {
        return $this->missionLogs()
            ->with('resources')
            ->orderBy('created_at', 'desc')
            ->paginate();
    }
}
