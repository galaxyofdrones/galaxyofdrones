<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Support\Util;

/**
 * Mission.
 *
 * @property int $id
 * @property int $planet_id
 * @property int $energy
 * @property int $experience
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $remaining
 * @property Planet $planet
 * @property \Illuminate\Database\Eloquent\Collection|resource[] $resources
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mission extends Model implements TimeableContract
{
    use Behaviors\Timeable,
        Relations\BelongsToPlanet;

    /**
     * The minimum capacity.
     *
     * @var float
     */
    const MIN_CAPACITY = 0.4;

    /**
     * The maximum capacity.
     *
     * @var float
     */
    const MAX_CAPACITY = 0.6;

    /**
     * The energy bonus.
     *
     * @var float
     */
    const ENERGY_BONUS = 6.0;

    /**
     * The experience bonus.
     *
     * @var float
     */
    const EXPERIENCE_BONUS = 3.0;

    /**
     * The mission time.
     *
     * @var int
     */
    const MISSION_TIME = 259200;

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
    protected $dates = [
        'ended_at',
    ];

    /**
     * Create a random mission.
     *
     * @param Planet     $planet
     * @param Building   $building
     * @param Collection $resources
     */
    public static function createRand(Planet $planet, Building $building, Collection $resources)
    {
        $mission = static::create([
            'planet_id' => $planet->id,
            'energy' => 0,
            'experience' => 0,
        ]);

        $resources = $resources->random(
            mt_rand(1, $resources->count())
        );

        $totalQuantity = $planet->capacity * static::randMultiplier();
        $totalFrequency = $resources->sum('frequency');

        foreach ($resources as $resource) {
            $quantity = round(
                $resource->frequency / $totalFrequency * $totalQuantity
            );

            $energy = round(
                $quantity * $resource->efficiency
            );

            $mission->energy += round(
                $energy * static::ENERGY_BONUS
            );

            $mission->experience += round(
                $energy * static::EXPERIENCE_BONUS
            );

            $mission->resources()->attach($resource->id, [
                'quantity' => $quantity,
            ]);
        }

        $mission->fill([
            'ended_at' => Carbon::now()->addSeconds(static::MISSION_TIME),
        ])->save();
    }

    /**
     * Delete the expired missions.
     *
     * @return bool|null
     */
    public static function deleteExpired()
    {
        return static::where('ended_at', '<', Carbon::now())->delete();
    }

    /**
     * Get a random multiplier.
     *
     * @return float
     */
    protected static function randMultiplier()
    {
        return static::MIN_CAPACITY + (static::MAX_CAPACITY - static::MIN_CAPACITY) * Util::randFloat();
    }

    /**
     * Get the resources.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources()
    {
        return $this->belongsToMany(Resource::class)->withPivot('quantity');
    }

    /**
     * Is completable?
     *
     * @return bool
     */
    public function isCompletable()
    {
        /** @var \Illuminate\Database\Eloquent\Collection|Stock[] $stocks */
        $stocks = $this->planet->findStocksByResourceIds($this->resources->modelKeys())
            ->keyBy('resource_id')
            ->each->setRelation('planet', $this->planet);

        foreach ($this->resources as $resource) {
            if (!$stocks->has($resource->id) || !$stocks->get($resource->id)->hasQuantity($resource->pivot->quantity)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        $this->planet->user->incrementEnergyAndExperience(
            $this->energy, $this->experience
        );

        /** @var \Illuminate\Database\Eloquent\Collection|Stock[] $stocks */
        $stocks = $this->planet->findStocksByResourceIds($this->resources->modelKeys())
            ->keyBy('resource_id')
            ->each->setRelation('planet', $this->planet);

        foreach ($this->resources as $resource) {
            $stocks->get($resource->id)->decrementQuantity($resource->pivot->quantity);
        }

        MissionLog::createFrom($this);

        $this->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        $this->delete();
    }
}
