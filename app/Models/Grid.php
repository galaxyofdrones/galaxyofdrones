<?php

namespace Koodilab\Models;

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
 * @property \Koodilab\Models\Building|null  $building
 * @property \Koodilab\Models\Construction   $construction
 * @property \Koodilab\Models\Planet         $planet
 * @property \Koodilab\Models\Training       $training
 * @property \Koodilab\Models\Upgrade        $upgrade
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Grid whereY($value)
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
