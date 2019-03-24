<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Mission log.
 *
 * @property int                                                                  $id
 * @property int                                                                  $user_id
 * @property int                                                                  $energy
 * @property int                                                                  $experience
 * @property \Illuminate\Support\Carbon|null                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                      $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Resource[] $resources
 * @property \Koodilab\Models\User                                                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\MissionLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\MissionLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\MissionLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\MissionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\MissionLog whereEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\MissionLog whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\MissionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\MissionLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\MissionLog whereUserId($value)
 * @mixin \Eloquent
 */
class MissionLog extends Model
{
    use Relations\BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

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
