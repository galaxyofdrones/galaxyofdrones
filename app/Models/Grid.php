<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Game\StateManager;

/**
 * Grid.
 *
 * @property int                 $id
 * @property int                 $planet_id
 * @property int|null            $building_id
 * @property int                 $x
 * @property int                 $y
 * @property int|null            $level
 * @property int                 $type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property Building|null       $building
 * @property Construction        $construction
 * @property Planet              $planet
 * @property Training            $training
 * @property Upgrade             $upgrade
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Grid whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grid whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grid whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grid wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grid whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grid whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grid whereY($value)
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

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function (self $grid) {
            app(StateManager::class)->syncPlanet($grid->planet);
        });
    }
}
