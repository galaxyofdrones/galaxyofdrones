<?php

namespace Koodilab\Models\Behaviors;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Support\Bounds;

trait Positionable
{
    /**
     * Get the travel time to the other.
     *
     * @param Model $other
     *
     * @return int
     */
    public function travelTimeTo(Model $other)
    {
        $distance = sqrt(pow($other->x - $this->x, 2) + pow($other->y - $this->y, 2));

        return round(
            $distance / static::AU_PER_PIXEL * static::TIME_OFFSET
        );
    }

    /**
     * In bounds scope.
     *
     * @param Builder $query
     * @param Bounds  $bounds
     *
     * @return Builder
     */
    public function scopeInBounds(Builder $query, Bounds $bounds)
    {
        $query->where('x', '>=', $bounds->minX())
            ->where('x', '<=', $bounds->maxX())
            ->where('y', '>=', $bounds->minY())
            ->where('y', '<=', $bounds->maxY());

        return $query;
    }

    /**
     * Get the penalty rate.
     *
     * @return double
     */
    public function penaltyRate()
    {
        $bounds = new Bounds(
            $this->x - static::PENALTY_STEP,
            $this->y - static::PENALTY_STEP,
            $this->x + static::PENALTY_STEP,
            $this->y + static::PENALTY_STEP
        );

        $planetsInBounds = static::inBounds($bounds)
            ->where('user_id', '=', $this->user_id)
            ->where('id', '!=', $this->id)
            ->count();

        $planetsCount = $this->user->planets()->where('id', '!=', $this->id)->count();
        $planetsRate = 1;

        if ($planetsCount > 0) {
            $planetsRate = $planetsInBounds / $planetsCount;
        }

        if ($planetsRate < static::PENALTY_RATE) {
            return 2 - $planetsRate;
        }

        return 1;
    }
}
