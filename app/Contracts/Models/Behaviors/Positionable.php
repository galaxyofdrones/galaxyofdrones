<?php

namespace App\Contracts\Models\Behaviors;

use App\Support\Bounds;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
     * @return int
     */
    public function travelTimeTo(Model $other);

    /**
     * In bounds scope.
     *
     * @return Builder
     */
    public function scopeInBounds(Builder $query, Bounds $bounds);
}
