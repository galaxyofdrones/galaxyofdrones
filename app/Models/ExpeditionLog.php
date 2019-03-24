<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Expedition log.
 *
 * @property int                                                              $id
 * @property int                                                              $star_id
 * @property int                                                              $user_id
 * @property int                                                              $solarion
 * @property int                                                              $experience
 * @property \Illuminate\Support\Carbon|null                                  $created_at
 * @property \Illuminate\Support\Carbon|null                                  $updated_at
 * @property \Koodilab\Models\Star                                            $star
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Unit[] $units
 * @property \Koodilab\Models\User                                            $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog whereSolarion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\ExpeditionLog whereUserId($value)
 * @mixin \Eloquent
 */
class ExpeditionLog extends Model
{
    use Relations\BelongsToStar,
        Relations\BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
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
