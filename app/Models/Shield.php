<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Shield.
 *
 * @property int                 $id
 * @property int                 $planet_id
 * @property \Carbon\Carbon      $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int                 $remaining
 * @property Planet              $planet
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Shield whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shield whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shield whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shield wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shield whereUpdatedAt($value)
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
