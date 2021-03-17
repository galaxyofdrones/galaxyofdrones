<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Expedition log.
 *
 * @property int                                                         $id
 * @property int                                                         $star_id
 * @property int                                                         $user_id
 * @property int                                                         $solarion
 * @property int                                                         $experience
 * @property \Illuminate\Support\Carbon|null                             $created_at
 * @property \Illuminate\Support\Carbon|null                             $updated_at
 * @property \App\Models\Star                                            $star
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[] $units
 * @property \App\Models\User                                            $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog whereSolarion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExpeditionLog whereUserId($value)
 * @mixin \Eloquent
 */
class ExpeditionLog extends Model
{
    use HasFactory,
        Relations\BelongsToStar,
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
