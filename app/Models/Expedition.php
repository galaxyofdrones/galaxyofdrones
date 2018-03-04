<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Expedition.
 *
 * @property int                                             $id
 * @property int                                             $user_id
 * @property int                                             $solarion
 * @property int                                             $experience
 * @property \Carbon\Carbon                                  $ended_at
 * @property \Carbon\Carbon|null                             $created_at
 * @property \Carbon\Carbon|null                             $updated_at
 * @property int                                             $remaining
 * @property \Illuminate\Database\Eloquent\Collection|Unit[] $units
 * @property User                                            $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Expedition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expedition whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expedition whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expedition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expedition whereSolarion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expedition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expedition whereUserId($value)
 * @mixin \Eloquent
 */
class Expedition extends Model
{
    use Behaviors\Timeable,
        Relations\BelongsToUser;

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
}
