<?php

namespace Koodilab\Contracts\Battle;

use Koodilab\Models\BattleLog;
use Koodilab\Models\Movement;

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
