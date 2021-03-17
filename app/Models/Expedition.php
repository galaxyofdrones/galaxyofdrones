<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Expedition.
 *
 * @property int                                                         $id
 * @property int                                                         $star_id
 * @property int                                                         $user_id
 * @property int                                                         $solarion
 * @property int                                                         $experience
 * @property \Illuminate\Support\Carbon                                  $ended_at
 * @property \Illuminate\Support\Carbon|null                             $created_at
 * @property \Illuminate\Support\Carbon|null                             $updated_at
 * @property int                                                         $remaining
 * @property \App\Models\Star                                            $star
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[] $units
 * @property \App\Models\User                                            $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition whereSolarion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expedition whereUserId($value)
 * @mixin \Eloquent
 */
class Expedition extends Model
{
    use HasFactory,
        Behaviors\Timeable,
        Queries\FindByStarAndUser,
        Relations\BelongsToStar,
        Relations\BelongsToUser;

    /**
     * The minimum supply.
     *
     * @var float
     */
    const MIN_SUPPLY = 0.01;

    /**
     * The maximum supply.
     *
     * @var float
     */
    const MAX_SUPPLY = 0.03;

    /**
     * The solarion count.
     *
     * @var int
     */
    const SOLARION_COUNT = 1;

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
     * Get the units.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function units()
    {
        return $this->belongsToMany(Unit::class)->withPivot('quantity');
    }

    /**
     * Is completable?
     *
     * @return bool
     */
    public function isCompletable()
    {
        $userUnits = $this->user->units()
            ->whereIn('unit_id', $this->units->modelKeys())
            ->get();

        foreach ($this->units as $unit) {
            $userUnit = $userUnits->firstWhere('id', $unit->id);

            if ($userUnit->pivot->quantity < $unit->pivot->quantity) {
                return false;
            }
        }

        return true;
    }
}
