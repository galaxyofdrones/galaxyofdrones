<?php

namespace Koodilab\Game;

use Carbon\Carbon;
use Koodilab\Models\Planet;
use Koodilab\Models\Shield;

class ShieldManager
{
    /**
     * Create.
     *
     * @param Planet   $planet
     * @param int|null $expirationTime
     *
     * @return Shield
     */
    public function create(Planet $planet, $expirationTime = null)
    {
        $expirationTime = $expirationTime ?: Shield::EXPIRATION_TIME;

        /** @var Shield $shield */
        $shield = Shield::firstOrNew([
            'planet_id' => $planet->id,
        ]);

        $now = Carbon::now();

        if (! $shield->ended_at || $shield->ended_at->lt($now)) {
            $shield->ended_at = $now;
        }

        $shield->fill([
            'ended_at' => $shield->ended_at->addSeconds($expirationTime),
        ])->save();

        return $shield;
    }

    /**
     * Create from solarion.
     *
     * @param Planet $planet
     *
     * @return Shield
     */
    public function createFromSolarion(Planet $planet)
    {
        $planet->user->decrementSolarion(
            Shield::SOLARION_COUNT
        );

        return $this->create($planet);
    }
}
