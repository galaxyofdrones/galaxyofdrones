<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Movement.
 *
 * @property int                                                 $id
 * @property int                                                 $start_id
 * @property int                                                 $end_id
 * @property int                                                 $user_id
 * @property int                                                 $type
 * @property \Carbon\Carbon                                      $ended_at
 * @property \Carbon\Carbon|null                                 $created_at
 * @property \Carbon\Carbon|null                                 $updated_at
 * @property Planet                                              $end
 * @property int                                                 $remaining
 * @property \Illuminate\Database\Eloquent\Collection|resource[] $resources
 * @property Planet                                              $start
 * @property \Illuminate\Database\Eloquent\Collection|Unit[]     $units
 * @property User                                                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereEndId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereStartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUserId($value)
 * @mixin \Eloquent
 */
class Movement extends Model
{
    use Behaviors\Timeable,
        Queries\FindResourcesOrderBySortOrder,
        Queries\FindUnitsOrderBySortOrder,
        Relations\BelongsToEnd,
        Relations\BelongsToStart,
        Relations\BelongsToUser;

    /**
     * The scout type.
     *
     * @var int
     */
    const TYPE_SCOUT = 0;

    /**
     * The attack type.
     *
     * @var int
     */
    const TYPE_ATTACK = 1;

    /**
     * The occupy type.
     *
     * @var int
     */
    const TYPE_OCCUPY = 2;

    /**
     * The support type.
     *
     * @var int
     */
    const TYPE_SUPPORT = 3;

    /**
     * The transport type.
     *
     * @var int
     */
    const TYPE_TRANSPORT = 4;

    /**
     * The trade type.
     *
     * @var int
     */
    const TYPE_TRADE = 5;

    /**
     * The patrol type.
     *
     * @var int
     */
    const TYPE_PATROL = 6;

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
     * Get the units.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function units()
    {
        return $this->belongsToMany(Unit::class)->withPivot('quantity');
    }
}
