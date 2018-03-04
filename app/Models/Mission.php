<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Support\Util;

/**
 * Mission.
 *
 * @property int                                                 $id
 * @property int                                                 $user_id
 * @property int                                                 $energy
 * @property int                                                 $experience
 * @property \Carbon\Carbon                                      $ended_at
 * @property \Carbon\Carbon|null                                 $created_at
 * @property \Carbon\Carbon|null                                 $updated_at
 * @property int                                                 $remaining
 * @property \Illuminate\Database\Eloquent\Collection|resource[] $resources
 * @property User                                                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereUserId($value)
 * @mixin \Eloquent
 */
class Mission extends Model implements TimeableContract
{
    use Behaviors\Timeable,
        Relations\BelongsToUser;

    /**
     * The minimum capacity.
     *
     * @var float
     */
    const MIN_CAPACITY = 0.1;

    /**
     * The maximum capacity.
     *
     * @var float
     */
    const MAX_CAPACITY = 0.3;

    /**
     * The energy bonus.
     *
     * @var float
     */
    const ENERGY_BONUS = 4.0;

    /**
     * The experience bonus.
     *
     * @var float
     */
    const EXPERIENCE_BONUS = 2.0;

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
     * @param User $user
     */
    public static function createRand(User $user)
    {
        $mission = static::create([
            'user_id' => $user->id,
            'energy' => 0,
            'experience' => 0,
        ]);

        $resources = $user->findMissionResources();

        $resources = $resources->random(
            mt_rand(1, $resources->count())
        );

        $totalFrequency = $resources->sum('frequency');
        $totalQuantity = $user->planets->sum('capacity') * static::randMultiplier();

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
        $userResources = $this->user->resources()
            ->whereIn('resource_id', $this->resources->modelKeys())
            ->get();

        foreach ($this->resources as $resource) {
            $userResource = $userResources->firstWhere('id', $resource->id);

            if ($userResource->pivot->quantity < $resource->pivot->quantity) {
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
        $this->user->incrementEnergyAndExperience(
            $this->energy,
            $this->experience
        );

        $userResources = $this->user->resources()
            ->whereIn('resource_id', $this->resources->modelKeys())
            ->get();

        foreach ($this->resources as $resource) {
            $userResource = $userResources->firstWhere('id', $resource->id);

            $userResource->pivot->update([
                'quantity' => max(0, $userResource->pivot->quantity - $resource->pivot->quantity),
            ]);
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

    /**
     * Get a random multiplier.
     *
     * @return float
     */
    protected static function randMultiplier()
    {
        return static::MIN_CAPACITY + (static::MAX_CAPACITY - static::MIN_CAPACITY) * Util::randFloat();
    }
}
