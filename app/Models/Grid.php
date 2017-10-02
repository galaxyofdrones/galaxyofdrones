<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Support\StateManager;

/**
 * Grid.
 *
 * @property int $id
 * @property int $planet_id
 * @property int|null $building_id
 * @property int $x
 * @property int $y
 * @property int|null $level
 * @property int $type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property Building|null $building
 * @property Construction $construction
 * @property Planet $planet
 * @property Training $training
 * @property Upgrade $upgrade
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
        Relations\HasOneTraining;

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
    protected $perPage = 30;

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

    /**
     * Get the construction buildings.
     *
     * @return Collection|Building[]
     */
    public function constructionBuildings()
    {
        if ($this->building_id) {
            return new Collection();
        }

        $modifiers = [
            'level' => 1,
            'defense_bonus' => $this->planet->defense_bonus,
            'construction_time_bonus' => $this->planet->construction_time_bonus,
        ];

        if ($this->construction) {
            return new Collection([
                $this->construction->building->applyModifiers($modifiers),
            ]);
        }

        $buildings = Building::defaultOrder()->whereIn(
            'parent_id', $this->planet->findNotEmptyGrids()->pluck('building_id')
        );

        if ($this->type == static::TYPE_RESOURCE) {
            $buildings->where('type', Building::TYPE_MINER);
        } else {
            $buildings->whereNotIn('type', [
                Building::TYPE_CENTRAL, Building::TYPE_MINER,
            ]);
        }

        return $buildings->get()
            ->filter(function (Building $building) {
                if ($building->limit) {
                    $count = $this->planet->grids()
                        ->where('building_id', $building->id)
                        ->count();

                    $count += $this->planet->constructions()
                        ->where('constructions.building_id', $building->id)
                        ->count();

                    return $building->limit > $count;
                }

                return true;
            })
            ->transform(function (Building $building) use ($modifiers) {
                return $building->applyModifiers($modifiers);
            });
    }

    /**
     * Demolish the building.
     *
     * @param int $level
     */
    public function demolishBuilding($level = null)
    {
        $level = $level ?: $this->level;

        if (empty($level) || !$this->building_id) {
            return;
        }

        if ($this->upgrade) {
            $this->upgrade->delete();
        }

        if ($this->training) {
            $this->training->delete();
        }

        $this->level = max(
            0, $this->level - $level
        );

        if (!$this->level) {
            $this->level = null;
            $this->building()->associate(null);
        }

        $this->save();
    }
}
