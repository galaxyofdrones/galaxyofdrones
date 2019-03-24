<?php

namespace Koodilab\Models;

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
 * @property \Koodilab\Models\Grid           $grid
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Upgrade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Upgrade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Upgrade query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Upgrade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Upgrade whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Upgrade whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Upgrade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Upgrade whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Upgrade whereUpdatedAt($value)
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
