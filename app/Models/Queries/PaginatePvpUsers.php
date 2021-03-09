<?php

namespace App\Models\Queries;

trait PaginatePvpUsers
{
    /**
     * Paginate the PvP users.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\Rank[]
     */
    public static function paginatePvpUsers($perPage = 10)
    {
        return static::join('users', 'ranks.user_id', '=', 'users.id')
            ->select(['ranks.*', 'users.username', 'users.experience'])
            ->orderBy('ranks.winning_battle_count', 'desc')
            ->paginate($perPage);
    }
}
