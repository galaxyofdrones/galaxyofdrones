<?php

namespace App\Models\Queries;

use App\Models\BattleLog;
use Illuminate\Database\Eloquent\Builder;

trait LosingBattleLogCount
{
    /**
     * Get the winning battle log count.
     *
     * @return int
     */
    public function losingBattleLogCount()
    {
        return BattleLog::where(function (Builder $query) {
            $query->where('attacker_id', $this->id)
                ->where('winner', BattleLog::WINNER_DEFENDER);
        })->orWhere(function (Builder $query) {
            $query->where('defender_id', $this->id)
                ->where('winner', BattleLog::WINNER_ATTACKER);
        })->count();
    }
}
