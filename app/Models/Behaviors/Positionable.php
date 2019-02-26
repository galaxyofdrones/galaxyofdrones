<?php

namespace Koodilab\Models\Behaviors;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Models\Planet;
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
     * Has penalty by the others' position.
     *
     * @return bool
     */
    public function hasPenalty()
    {
        return $this->penaltyRate() > Planet::PENALTY_RATE;
    }

    /**
     * Get penalty rate by the others' position.
     *
     * @return double
     */
    public function penaltyRate()
    {
        $bounds = new Bounds(
            $this->x - Planet::PENALTY_STEP,
            $this->y - Planet::PENALTY_STEP,
            $this->x + Planet::PENALTY_STEP,
            $this->y + Planet::PENALTY_STEP
        );

        $query = Planet::query()->where('user_id', '=', $this->user_id);

        return $this->scopeInBounds($query, $bounds)->count() / $this->user->planets()->count();
    }
}
