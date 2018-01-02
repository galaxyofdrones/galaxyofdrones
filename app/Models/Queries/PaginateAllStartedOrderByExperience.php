<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\User;

trait PaginateAllStartedOrderByExperience
{
    /**
     * Paginate all started order by experience.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|User[]
     */
    public static function paginateAllStartedOrderByExperience()
    {
        return static::whereNotNull('started_at')
            ->orderBy('experience', 'desc')
            ->paginate();
    }
}
