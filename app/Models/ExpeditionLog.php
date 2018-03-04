<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Expedition log.
 *
 * @property int                                             $id
 * @property int                                             $resource_id
 * @property int                                             $user_id
 * @property int                                             $resource_quantity
 * @property int                                             $experience
 * @property \Carbon\Carbon|null                             $created_at
 * @property \Carbon\Carbon|null                             $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|Unit[] $units
 * @property User                                            $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ExpeditionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpeditionLog whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpeditionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpeditionLog whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpeditionLog whereResourceQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpeditionLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpeditionLog whereUserId($value)
 * @mixin \Eloquent
 */
class ExpeditionLog extends Model
{
    use Relations\BelongsToUser;

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
