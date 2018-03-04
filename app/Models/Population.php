<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Population.
 *
 * @property int                 $id
 * @property int                 $planet_id
 * @property int                 $unit_id
 * @property int                 $quantity
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property Planet              $planet
 * @property Unit                $unit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Population extends Model
{
    use Concerns\HasUnitQuantity,
        Relations\BelongsToPlanet,
        Relations\BelongsToUnit;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];
}
