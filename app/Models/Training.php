<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Training.
 *
 * @property int                 $id
 * @property int                 $grid_id
 * @property int                 $unit_id
 * @property int                 $quantity
 * @property \Carbon\Carbon      $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int                 $remaining
 * @property Grid                $grid
 * @property Unit                $unit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Training extends Model
{
    use Behaviors\Timeable,
        Relations\BelongsToGrid,
        Relations\BelongsToUnit;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'ended_at',
    ];
}
