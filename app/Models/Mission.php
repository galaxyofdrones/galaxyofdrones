<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Events\PlanetUpdated;
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
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mission extends Model implements TimeableContract
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
     * {@inheritdoc}
     */
    public function finish()
    {
        $this->planet->user->experience += $this->experience;
        $this->planet->user->save();

        /** @var \Illuminate\Database\Eloquent\Collection|Stock[] $stocks */
        $stocks = $this->planet->stocks()
            ->whereIn('resource_id', $this->resources->modelKeys())
            ->get()
            ->keyBy('resource_id');

        foreach ($this->resources as $resource) {
            if (!$stocks->has($resource->id)) {
                return false;
            }

            /** @var Stock $stock */
            $stock = $stocks
                ->get($resource->id)
                ->setRelation('planet', $this->planet);

            if (!$stock->hasQuantity($resource->pivot->quantity)) {
                return false;
            }
        }

        foreach ($this->resources as $resource) {
            $stocks->get($resource->id)->decrementQuantity($resource->pivot->quantity);
        }

        MissionLog::createFromMission($this);
        $this->delete();

        event(new PlanetUpdated($this->planet_id));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        $this->delete();
    }
}
