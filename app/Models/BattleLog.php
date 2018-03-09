<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Battle log.
 *
 * @property int                                                 $id
 * @property int                                                 $start_id
 * @property int                                                 $end_id
 * @property int                                                 $attacker_id
 * @property int|null                                            $defender_id
 * @property string                                              $start_name
 * @property string                                              $end_name
 * @property int                                                 $type
 * @property int                                                 $winner
 * @property \Carbon\Carbon|null                                 $created_at
 * @property \Carbon\Carbon|null                                 $updated_at
 * @property User                                                $attacker
 * @property \Illuminate\Database\Eloquent\Collection|Unit[]     $attackerUnits
 * @property \Kalnoy\Nestedset\Collection|Building[]             $buildings
 * @property User|null                                           $defender
 * @property \Illuminate\Database\Eloquent\Collection|Unit[]     $defenderUnits
 * @property Planet                                              $end
 * @property \Illuminate\Database\Eloquent\Collection|resource[] $resources
 * @property Planet                                              $start
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereAttackerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereDefenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereEndId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereEndName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereStartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereStartName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BattleLog whereWinner($value)
 * @mixin \Eloquent
 */
class BattleLog extends Model
{
    use Relations\BelongsToEnd,
        Relations\BelongsToStart;

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
     * The winner is attacker.
     *
     * @var int
     */
    const WINNER_ATTACKER = 0;

    /**
     * The winner is defender.
     *
     * @var int
     */
    const WINNER_DEFENDER = 1;

    /**
     * The owner is attacker.
     *
     * @var int
     */
    const OWNER_ATTACKER = 0;

    /**
     * The owner is defender.
     *
     * @var int
     */
    const OWNER_DEFENDER = 1;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * Get the attacker.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attacker()
    {
        return $this->belongsTo(User::class, 'attacker_id');
    }

    /**
     * Get the defender.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defender()
    {
        return $this->belongsTo(User::class, 'defender_id');
    }

    /**
     * Get the resources.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources()
    {
        return $this->belongsToMany(Resource::class)->withPivot('quantity', 'losses');
    }

    /**
     * Get the buildings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function buildings()
    {
        return $this->belongsToMany(Building::class)->withPivot('level', 'losses');
    }

    /**
     * Get the attacker units.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attackerUnits()
    {
        return $this->belongsToMany(Unit::class)
            ->wherePivot('owner', static::OWNER_ATTACKER)
            ->withPivot('quantity', 'losses');
    }

    /**
     * Get the defender units.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function defenderUnits()
    {
        return $this->belongsToMany(Unit::class)
            ->wherePivot('owner', static::OWNER_DEFENDER)
            ->withPivot('quantity', 'losses');
    }
}
