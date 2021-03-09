<?php

namespace App\Models\Queries;

use App\Models\BattleLog;
use Illuminate\Database\Eloquent\Builder;

trait WinningBattleLogCount
{
    /**
     * Get the winning battle log count.
     *
     * @return int
     */
    public function winningBattleLogCount()
    {
        return BattleLog::where(function (Builder $query) {
            $query->where('attacker_id', $this->id)
                ->where('winner', BattleLog::WINNER_ATTACKER);
        })->orWhere(function (Builder $query) {
            $query->where('defender_id', $this->id)
                ->where('winner', BattleLog::WINNER_DEFENDER);
        })->count();
    }
}
