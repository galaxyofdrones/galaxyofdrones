<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Shield.
 *
 * @property int                             $id
 * @property int                             $planet_id
 * @property \Illuminate\Support\Carbon      $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int                             $remaining
 * @property \Koodilab\Models\Planet         $planet
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Shield newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Shield newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Shield query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Shield whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Shield whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Shield whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Shield wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Shield whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Shield extends Model
{
    use Behaviors\Timeable,
        Relations\BelongsToPlanet;

    /**
     * The expiration time.
     *
     * @var int
     */
    const EXPIRATION_TIME = 21600;

    /**
     * The start expiration time.
     *
     * @var int
     */
    const START_EXPIRATION_TIME = 604800;

    /**
     * The solarion count.
     *
     * @var int
     */
    const SOLARION_COUNT = 1;

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
     * Get the expiration.
     *
     * @return float
     */
    public static function expiration()
    {
        return round(static::EXPIRATION_TIME / 60 / 60);
    }
}
