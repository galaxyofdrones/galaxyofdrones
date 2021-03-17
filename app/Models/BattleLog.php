<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Battle log.
 *
 * @property int                                                             $id
 * @property int                                                             $start_id
 * @property int                                                             $end_id
 * @property int                                                             $attacker_id
 * @property int|null                                                        $defender_id
 * @property string                                                          $start_name
 * @property string                                                          $end_name
 * @property int                                                             $type
 * @property int                                                             $winner
 * @property \Illuminate\Support\Carbon|null                                 $created_at
 * @property \Illuminate\Support\Carbon|null                                 $updated_at
 * @property \App\Models\User                                                $attacker
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[]     $attackerUnits
 * @property \Kalnoy\Nestedset\Collection|\App\Models\Building[]             $buildings
 * @property \App\Models\User|null                                           $defender
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[]     $defenderUnits
 * @property \App\Models\Planet                                              $end
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Resource[] $resources
 * @property \App\Models\Planet                                              $start
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereAttackerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereDefenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereEndId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereEndName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereStartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereStartName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BattleLog whereWinner($value)
 * @mixin \Eloquent
 */
class BattleLog extends Model
{
    use HasFactory,
        Relations\BelongsToEnd,
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
