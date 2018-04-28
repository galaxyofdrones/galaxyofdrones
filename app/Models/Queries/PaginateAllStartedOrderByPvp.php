<?php

namespace Koodilab\Models\Queries;

use Illuminate\Database\Eloquent\Builder;
use Koodilab\Models\BattleLog;

trait PaginateAllStartedOrderByPvp
{
    /**
     * Paginate all started order by pvp.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Koodilab\Models\User[]
     */
    public static function paginateAllStartedOrderByPvp($perPage = 10)
    {
        $query = static::select(['users.id', 'users.username'])
            ->whereNotNull('started_at')
            ->withCount('planets')
            ->orderBy('winning_battle_count', 'desc');

        $query->selectSub(BattleLog::selectRaw('COUNT(*)')
            ->where(function (Builder $query) {
                $query->whereRaw('attacker_id = users.id')
                    ->where('winner', BattleLog::WINNER_ATTACKER);
            })->orWhere(function (Builder $query) {
                $query->whereRaw('defender_id = users.id')
                    ->where('winner', BattleLog::WINNER_DEFENDER);
            })
            ->toBase(), 'winning_battle_count');

        $query->selectSub(BattleLog::selectRaw('COUNT(*)')
            ->where(function (Builder $query) {
                $query->whereRaw('attacker_id = users.id')
                    ->where('winner', BattleLog::WINNER_DEFENDER);
            })->orWhere(function (Builder $query) {
                $query->whereRaw('defender_id = users.id')
                    ->where('winner', BattleLog::WINNER_ATTACKER);
            })
            ->toBase(), 'losing_battle_count');

        return $query->paginate($perPage);
    }
}
