<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Grid.
 *
 * @property int                             $id
 * @property int                             $planet_id
 * @property int|null                        $building_id
 * @property int                             $x
 * @property int                             $y
 * @property int|null                        $level
 * @property int                             $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Building|null       $building
 * @property \App\Models\Construction        $construction
 * @property \App\Models\Planet              $planet
 * @property \App\Models\Training            $training
 * @property \App\Models\Upgrade             $upgrade
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grid whereY($value)
 * @mixin \Eloquent
 */
class Grid extends Model
{
    use Relations\BelongsToBuilding,
        Relations\BelongsToPlanet,
        Relations\HasOneConstruction,
        Relations\HasOneUpgrade,
        Relations\HasOneTraining,
        Queries\FindAllByBuilding,
        Concerns\HasBuilding,
        Concerns\HasUnit;

    /**
     * The plain type.
     *
     * @var int
     */
    const TYPE_PLAIN = 0;

    /**
     * The resource type.
     *
     * @var int
     */
    const TYPE_RESOURCE = 1;

    /**
     * The central type.
     *
     * @var int
     */
    const TYPE_CENTRAL = 2;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_enabled' => 'bool',
    ];
}
