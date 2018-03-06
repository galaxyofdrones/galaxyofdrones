<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Upgrade.
 *
 * @property int                 $id
 * @property int                 $grid_id
 * @property int                 $level
 * @property \Carbon\Carbon      $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int                 $remaining
 * @property Grid                $grid
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Upgrade extends Model
{
    use Behaviors\Timeable,
        Relations\BelongsToGrid;

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
