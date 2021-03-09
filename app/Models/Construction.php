<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Construction.
 *
 * @property int                             $id
 * @property int                             $building_id
 * @property int                             $grid_id
 * @property int                             $level
 * @property \Illuminate\Support\Carbon      $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Building            $building
 * @property int                             $remaining
 * @property \App\Models\Grid                $grid
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Construction whereUpdatedAt($value)
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
