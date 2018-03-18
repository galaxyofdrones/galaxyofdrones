<?php

namespace Koodilab\Models\Queries;

trait PaginateAllStartedOrderByPve
{
    /**
     * Paginate all started order by pve.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Koodilab\Models\User[]
     */
    public static function paginateAllStartedOrderByPve($perPage = 10)
    {
        return static::whereNotNull('started_at')
            ->withCount('missionLogs', 'expeditionLogs')
            ->orderBy('experience', 'desc')
            ->paginate($perPage);
    }
}
