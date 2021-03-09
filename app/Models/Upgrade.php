<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Upgrade.
 *
 * @property int                             $id
 * @property int                             $grid_id
 * @property int                             $level
 * @property \Illuminate\Support\Carbon      $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int                             $remaining
 * @property \App\Models\Grid                $grid
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Upgrade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Upgrade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Upgrade query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Upgrade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Upgrade whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Upgrade whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Upgrade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Upgrade whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Upgrade whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Upgrade extends Model
{
    use Behaviors\Timeable,
        Relations\BelongsToGrid;

    /**
     * The solarion count.
     *
     * @var int
     */
    const SOLARION_COUNT = 1;

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
