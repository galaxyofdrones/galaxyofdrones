<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Training.
 *
 * @property int                             $id
 * @property int                             $grid_id
 * @property int                             $unit_id
 * @property int                             $quantity
 * @property \Illuminate\Support\Carbon      $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int                             $remaining
 * @property \Koodilab\Models\Grid           $grid
 * @property \Koodilab\Models\Unit           $unit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Training whereUpdatedAt($value)
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
