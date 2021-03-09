<?php

namespace App\Models\Concerns;

use App\Models\Planet;
use App\Support\Bounds;

trait HasPenaltyRate
{
    /**
     * Calculate the penalty rate.
     *
     * @return float
     */
    public function calculatePenaltyRate()
    {
        if (! $this->capital_id) {
            return 0;
        }

        $bounds = new Bounds(
            $this->capital->x - Planet::PENALTY_STEP,
            $this->capital->y - Planet::PENALTY_STEP,
            $this->capital->x + Planet::PENALTY_STEP,
            $this->capital->y + Planet::PENALTY_STEP
        );

        $surroundingPlanetCount = Planet::inBounds($bounds)
            ->where('user_id', '=', $this->id)
            ->where('id', '!=', $this->capital_id)
            ->count();

        $planetCount = $this->planets()
            ->where('id', '!=', $this->capital_id)
            ->count();

        $planetFrequency = 1;

        if ($planetCount > 0) {
            $planetFrequency = $surroundingPlanetCount / $planetCount;
        }

        if ($planetFrequency < Planet::PENALTY_RATE) {
            return 1 - round($planetFrequency, 2);
        }

        return 0;
    }
}
