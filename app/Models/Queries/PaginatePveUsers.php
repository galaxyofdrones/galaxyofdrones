<?php

namespace Koodilab\Models\Queries;

trait PaginatePveUsers
{
    /**
     * Paginate the PvE users.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Koodilab\Models\Rank[]
     */
    public static function paginatePveUsers($perPage = 10)
    {
        return static::join('users', 'ranks.user_id', '=', 'users.id')
            ->select(['ranks.*', 'users.username', 'users.experience'])
            ->orderBy('users.experience', 'desc')
            ->paginate($perPage);
    }
}
