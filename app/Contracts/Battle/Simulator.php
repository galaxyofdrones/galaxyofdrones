<?php

namespace Koodilab\Contracts\Battle;

use Koodilab\Models\BattleLog;
use Koodilab\Models\Movement;

interface Simulator
{
    /**
     * Scout.
     *
     * @param Movement $movement
     *
     * @return BattleLog
     */
    public function scout(Movement $movement);

    /**
     * Attack.
     *
     * @param Movement $movement
     *
     * @return BattleLog
     */
    public function attack(Movement $movement);

    /**
     * Occupy.
     *
     * @param Movement $movement
     *
     * @return BattleLog
     */
    public function occupy(Movement $movement);
}
