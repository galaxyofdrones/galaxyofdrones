<?php

namespace Koodilab\Game;

use Koodilab\Models\BattleLog;
use Koodilab\Models\Movement;
use Koodilab\Notifications\BattleLogCreated;

class BattleManager
{
    /**
     * Create log.
     *
     * @param Movement $movement
     * @param bool     $winner
     *
     * @return BattleLog
     */
    public function createLog(Movement $movement, $winner = null)
    {
        $battleLog = BattleLog::create([
            'attacker_id' => $movement->start->user_id,
            'defender_id' => $movement->end->user_id,
            'start_id' => $movement->start_id,
            'end_id' => $movement->end_id,
            'start_name' => $movement->start->display_name,
            'end_name' => $movement->end->display_name,
            'type' => $movement->type,
            'winner' => $winner ?: BattleLog::WINNER_ATTACKER,
        ]);

        if ($battleLog->type == BattleLog::TYPE_SCOUT) {
            $battleLog->attacker->notify(
                new BattleLogCreated($battleLog->id)
            );

            if ($battleLog->defender_id && $battleLog->winner == BattleLog::WINNER_DEFENDER) {
                $battleLog->defender->notify(
                    new BattleLogCreated($battleLog->id)
                );
            }
        } else {
            $battleLog->attacker->notify(
                new BattleLogCreated($battleLog->id)
            );

            if ($battleLog->defender_id) {
                $battleLog->defender->notify(
                    new BattleLogCreated($battleLog->id)
                );
            }
        }

        return $battleLog;
    }
}
