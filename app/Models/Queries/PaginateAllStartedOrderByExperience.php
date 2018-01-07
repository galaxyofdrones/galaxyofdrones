<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\User;

trait PaginateAllStartedOrderByExperience
{
    /**
     * Paginate all started order by experience.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|User[]
     */
    public static function paginateAllStartedOrderByExperience($perPage = 1)
    {
        return static::whereNotNull('started_at')
            ->orderBy('experience', 'desc')
            ->paginate($perPage);
    }
}
