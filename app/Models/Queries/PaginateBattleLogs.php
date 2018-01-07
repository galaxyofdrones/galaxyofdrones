<?php

namespace Koodilab\Models\Queries;

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
            ->orWhere('defender_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
