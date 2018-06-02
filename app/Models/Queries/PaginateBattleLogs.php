<?php

namespace Koodilab\Models\Queries;

use Illuminate\Database\Eloquent\Builder;
use Koodilab\Models\BattleLog;

trait PaginateBattleLogs
{
    /**
     * Paginate the battle logs.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|BattleLog[]
     */
    public function paginateBattleLogs($perPage = 5)
    {
        return BattleLog::with('start', 'end', 'attacker', 'defender', 'resources', 'buildings', 'attackerUnits', 'defenderUnits')
            ->where('attacker_id', $this->id)
            ->orWhere(function (Builder $query) {
                $query->where('defender_id', $this->id)
                    ->where('type', '!=', BattleLog::TYPE_SCOUT);
            })
            ->orWhere(function (Builder $query) {
                $query->where('defender_id', $this->id)
                    ->where('type', BattleLog::TYPE_SCOUT)
                    ->where('winner', BattleLog::WINNER_DEFENDER);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
