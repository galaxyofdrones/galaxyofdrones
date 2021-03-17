<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mission.
 *
 * @property int                                                             $id
 * @property int                                                             $user_id
 * @property int                                                             $energy
 * @property int                                                             $experience
 * @property \Illuminate\Support\Carbon                                      $ended_at
 * @property \Illuminate\Support\Carbon|null                                 $created_at
 * @property \Illuminate\Support\Carbon|null                                 $updated_at
 * @property int                                                             $remaining
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Resource[] $resources
 * @property \App\Models\User                                                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission whereEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mission whereUserId($value)
 * @mixin \Eloquent
 */
class Mission extends Model
{
    use HasFactory,
        Behaviors\Timeable,
        Relations\BelongsToUser;

    /**
     * The minimum capacity.
     *
     * @var float
     */
    const MIN_CAPACITY = 0.2;

    /**
     * The maximum capacity.
     *
     * @var float
     */
    const MAX_CAPACITY = 0.4;

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
     * The expiration time.
     *
     * @var int
     */
    const EXPIRATION_TIME = 259200;

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
}
