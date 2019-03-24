<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Movement.
 *
 * @property int                                                                  $id
 * @property int                                                                  $start_id
 * @property int                                                                  $end_id
 * @property int                                                                  $user_id
 * @property int                                                                  $type
 * @property \Illuminate\Support\Carbon                                           $ended_at
 * @property \Illuminate\Support\Carbon|null                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                      $updated_at
 * @property \Koodilab\Models\Planet                                              $end
 * @property int                                                                  $remaining
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Resource[] $resources
 * @property \Koodilab\Models\Planet                                              $start
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Unit[]     $units
 * @property \Koodilab\Models\User                                                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement whereEndId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement whereStartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Movement whereUserId($value)
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
