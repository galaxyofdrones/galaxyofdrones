<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Construction.
 *
 * @property int                 $id
 * @property int                 $building_id
 * @property int                 $grid_id
 * @property int                 $level
 * @property \Carbon\Carbon      $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property Building            $building
 * @property int                 $remaining
 * @property Grid                $grid
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Construction extends Model
{
    use Behaviors\Timeable,
        Relations\BelongsToBuilding,
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
