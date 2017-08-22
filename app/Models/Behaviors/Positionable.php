<?php

namespace Koodilab\Models\Behaviors;

use Illuminate\Database\Eloquent\Builder;
use Koodilab\Support\Bounds;

trait Positionable
{
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
        $query
            ->where('x', '>=', $bounds->minX())
            ->where('x', '<=', $bounds->maxX())
            ->where('y', '>=', $bounds->minY())
            ->where('y', '<=', $bounds->maxY());

        return $query;
    }
}
