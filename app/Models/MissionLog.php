<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Models\Relations\BelongsToUser;

/**
 * Mission log.
 *
 * @property int $id
 * @property int $user_id
 * @property int $experience
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|resource[] $resources
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissionLog whereUserId($value)
 * @mixin \Eloquent
 */
class MissionLog extends Model
{
    use BelongsToUser;

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
     * Create from a mission.
     *
     * @param Mission $mission
     *
     * @return MissionLog
     */
    public static function createFromMission(Mission $mission)
    {
        $missionLog = new self([
            'experience' => $mission->experience,
        ]);

        $missionLog->user()->associate($mission->planet->user_id);
        $missionLog->save();

        foreach ($mission->resources as $resource) {
            $missionLog->resources()->attach($resource->id, [
                'quantity' => $resource->pivot->quantity,
            ]);
        }

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
