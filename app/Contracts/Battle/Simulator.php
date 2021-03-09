<?php

namespace App\Contracts\Battle;

use App\Models\BattleLog;
use App\Models\Movement;

interface Simulator
{
    /**
     * Scout.
     *
     * @return BattleLog
     */
    public function scout(Movement $movement);

    /**
     * Attack.
     *
     * @return BattleLog
     */
    public function attack(Movement $movement);

    /**
     * Occupy.
     *
     * @return BattleLog
     */
    public function occupy(Movement $movement);
}
