<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\BattleLog;

trait PaginateBattleLogs
{
    /**
     * Paginate the battle logs.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|BattleLog[]
     */
    public function paginateBattleLogs()
    {
        return BattleLog::where('attacker_id', $this->id)
            ->orWhere('defender_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->paginate(1);
    }
}
