<?php

namespace App\Models\Behaviors;

use App\Support\Bounds;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Positionable
{
    /**
     * Get the travel time to the other.
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
}
