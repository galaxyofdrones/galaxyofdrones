<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Notifications\MissionLogCreated;

/**
 * Mission log.
 *
 * @property int $id
 * @property int $user_id
 * @property int $energy
 * @property int $experience
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|resource[] $resources
 * @property User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereUserId($value)
 * @mixin \Eloquent
 */
class MissionLog extends Model
{
    use Relations\BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $perPage = 5;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * Create from.
     *
     * @param Mission $mission
     *
     * @return MissionLog
     */
    public static function createFrom(Mission $mission)
    {
        $missionLog = static::create([
            'user_id' => $mission->planet->user_id,
            'energy' => $mission->energy,
            'experience' => $mission->experience,
        ]);

        foreach ($mission->resources as $resource) {
            $missionLog->resources()->attach($resource->id, [
                'quantity' => $resource->pivot->quantity,
            ]);
        }

        $missionLog->user->notify(
            new MissionLogCreated($missionLog->id)
        );

        return $missionLog;
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
}
