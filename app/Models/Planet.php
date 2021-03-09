<?php

namespace App\Models;

use App\Contracts\Models\Behaviors\Positionable as PositionableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Planet.
 *
 * @property int                                                                 $id
 * @property int                                                                 $resource_id
 * @property int|null                                                            $user_id
 * @property string                                                              $name
 * @property string|null                                                         $custom_name
 * @property int                                                                 $x
 * @property int                                                                 $y
 * @property int                                                                 $size
 * @property int|null                                                            $capacity
 * @property int|null                                                            $supply
 * @property int|null                                                            $mining_rate
 * @property int|null                                                            $production_rate
 * @property float|null                                                          $defense_bonus
 * @property float|null                                                          $construction_time_bonus
 * @property \Illuminate\Support\Carbon|null                                     $created_at
 * @property \Illuminate\Support\Carbon|null                                     $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Construction[] $constructions
 * @property string                                                              $display_name
 * @property int                                                                 $free_capacity
 * @property int                                                                 $free_supply
 * @property int                                                                 $resource_count
 * @property int                                                                 $used_capacity
 * @property int                                                                 $used_supply
 * @property int                                                                 $used_training_supply
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Grid[]         $grids
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Movement[]     $incomingMovements
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Movement[]     $outgoingMovements
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Population[]   $populations
 * @property \App\Models\Resource                                                $resource
 * @property \App\Models\Shield                                                  $shield
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Stock[]        $stocks
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Training[]     $trainings
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Upgrade[]      $upgrades
 * @property \App\Models\User|null                                               $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet inBounds(\App\Support\Bounds $bounds)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet starter()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereConstructionTimeBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereCustomName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereDefenseBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereMiningRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereProductionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Planet whereY($value)
 * @mixin \Eloquent
 */
class Planet extends Model implements PositionableContract
{
    use Behaviors\Positionable,
        Concerns\HasCapacity,
        Concerns\HasCustomName,
        Concerns\HasGrid,
        Concerns\HasShield,
        Concerns\HasSupply,
        Queries\FindBuildings,
        Queries\FindFreeCapital,
        Queries\FindGrids,
        Queries\FindIncomingMovements,
        Queries\FindNotEmptyGrids,
        Queries\FindOutgoingMovements,
        Queries\FindPopulationByUnit,
        Queries\FindPopulationsByUnitIds,
        Queries\FindStockByResource,
        Queries\FindStocksByResourceIds,
        Queries\IncomingMovementCount,
        Queries\IncomingAttackMovementCount,
        Queries\IncomingCapitalMovementCount,
        Queries\OutgoingMovementCount,
        Queries\OutgoingAttackMovementCount,
        Relations\BelongsToResource,
        Relations\BelongsToUser,
        Relations\HasManyStock,
        Relations\HasManyPopulation,
        Relations\HasManyGrid,
        Relations\HasOneShield;

    /**
     * The small size.
     *
     * @var int
     */
    const SIZE_SMALL = 0;

    /**
     * The medium size.
     *
     * @var int
     */
    const SIZE_MEDIUM = 1;

    /**
     * The large size.
     *
     * @var int
     */
    const SIZE_LARGE = 2;

    /**
     * The resource count.
     *
     * @var int
     */
    const RESOURCE_COUNT = 3;

    /**
     * The settler count.
     *
     * @var int
     */
    const SETTLER_COUNT = 1;

    /**
     * The find step.
     *
     * @var int
     */
    const FIND_STEP = 1024;

    /**
     * The penalty step.
     *
     * @var int
     */
    const PENALTY_STEP = 4096;

    /**
     * The penalty rate.
     *
     * @var int
     */
    const PENALTY_RATE = 0.5;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * Get the incoming movements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incomingMovements()
    {
        return $this->hasMany(Movement::class, 'end_id');
    }

    /**
     * Get the outgoing movements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outgoingMovements()
    {
        return $this->hasMany(Movement::class, 'start_id');
    }

    /**
     * Get the constructions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function constructions()
    {
        return $this->hasManyThrough(Construction::class, Grid::class);
    }

    /**
     * Get the upgrades.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function upgrades()
    {
        return $this->hasManyThrough(Upgrade::class, Grid::class);
    }

    /**
     * Get the trainings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function trainings()
    {
        return $this->hasManyThrough(Training::class, Grid::class);
    }

    /**
     * Is capital?
     *
     * @return bool
     */
    public function isCapital()
    {
        return $this->user_id && $this->id == $this->user->capital_id;
    }

    /**
     * Get the resource count attribute.
     *
     * @return int
     */
    public function getResourceCountAttribute()
    {
        return static::RESOURCE_COUNT + $this->size;
    }

    /**
     * Starter scope.
     *
     * @return Builder
     */
    public function scopeStarter(Builder $query)
    {
        $resourceId = Resource::where('is_unlocked', true)
            ->orderBy('sort_order')
            ->value('id');

        return $query
            ->whereNull('user_id')
            ->where('resource_id', $resourceId)
            ->where('size', static::SIZE_SMALL);
    }
}
