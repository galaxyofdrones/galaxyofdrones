<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Koodilab\Models\Behaviors\Timeable;
use Koodilab\Models\Relations\BelongsToPlanet;

/**
 * Mission.
 *
 * @property int $id
 * @property int $planet_id
 * @property int $experience
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read int $remaining
 * @property-read Planet $planet
 * @property-read \Illuminate\Database\Eloquent\Collection|resource[] $resources
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Mission expired()
 * @method static \Illuminate\Database\Eloquent\Builder|Mission notExpired()
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mission extends Model
{
    use Timeable, BelongsToPlanet;

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
        $mission = new self([
            'ended_at' => Carbon::now()->addSeconds($building->mission_time),
        ]);

        $mission->planet()->associate($planet->id);
        $mission->save();

        $amount = mt_rand(1, $resources->count());

        $resources = $amount == 1
            ? new Collection([$resources->random($amount)])
            : $resources->random($amount);

        $randQuantity = static::MIN_CAPACITY + (static::MAX_CAPACITY - static::MIN_CAPACITY) * ((float) mt_rand() / (float) mt_getrandmax());
        $totalQuantity = $planet->capacity * $randQuantity;

        $totalFrequency = $resources->sum('frequency');

        foreach ($resources as $resource) {
            $quantity = round($resource->frequency / $totalFrequency * $totalQuantity);
            $mission->experience += $quantity + round($quantity * $resource->efficiency);

            $mission->resources()->attach($resource->id, [
                'quantity' => $quantity,
            ]);
        }

        $mission->save();
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
     * Expired scope.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeExpired(Builder $query)
    {
        return $query->where('ended_at', '<', Carbon::now());
    }

    /**
     * Expired scope.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeNotExpired(Builder $query)
    {
        return $query->where('ended_at', '>=', Carbon::now());
    }
}
