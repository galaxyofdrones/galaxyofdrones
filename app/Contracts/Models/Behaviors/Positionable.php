<?php

namespace Koodilab\Contracts\Models\Behaviors;

use Illuminate\Database\Eloquent\Builder;
use Koodilab\Support\Bounds;

interface Positionable
{
    /**
     * In bounds scope.
     *
     * @param Builder $query
     * @param Bounds  $bounds
     *
     * @return Builder
     */
    public function scopeInBounds(Builder $query, Bounds $bounds);

    /**
     * To feature.
     *
     * @return array
     */
    public function toFeature();
}
