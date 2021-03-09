<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Mission log.
 *
 * @property int                                                             $id
 * @property int                                                             $user_id
 * @property int                                                             $energy
 * @property int                                                             $experience
 * @property \Illuminate\Support\Carbon|null                                 $created_at
 * @property \Illuminate\Support\Carbon|null                                 $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Resource[] $resources
 * @property \App\Models\User                                                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MissionLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MissionLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MissionLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MissionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MissionLog whereEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MissionLog whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MissionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MissionLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MissionLog whereUserId($value)
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
