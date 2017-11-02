<?php

namespace Koodilab\Contracts\Models\Behaviors;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Support\Bounds;

interface Positionable
{
    /**
     * The astronomical unit per pixel.
     *
     * @var int
     */
    const AU_PER_PIXEL = 256;

    /**
     * The time offset.
     *
     * @var int
     */
    const TIME_OFFSET = 500;

    /**
     * Get the travel time to the other.
     *
     * @param Model $other
     *
     * @return int
     */
    public function travelTimeTo(Model $other);

    /**
     * In bounds scope.
     *
     * @param Builder $query
     * @param Bounds  $bounds
     *
     * @return Builder
     */
    public function scopeInBounds(Builder $query, Bounds $bounds);
}
