<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Battle log.
 *
 * @property int $id
 * @property int $start_id
 * @property int $end_id
 * @property int $attacker_id
 * @property int|null $defender_id
 * @property string $start_name
 * @property string $end_name
 * @property int $type
 * @property int $winner
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $attacker
 * @property-read \Illuminate\Database\Eloquent\Collection|Unit[] $attackerUnits
 * @property-read \Illuminate\Database\Eloquent\Collection|Building[] $buildings
 * @property-read User|null $defender
 * @property-read \Illuminate\Database\Eloquent\Collection|Unit[] $defenderUnits
 * @property-read Planet $end
 * @property-read \Illuminate\Database\Eloquent\Collection|resource[] $resources
 * @property-read Planet $start
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
    protected $perPage = 30;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * Create from a movement.
     *
     * @param Movement $movement
     * @param bool     $winner
     *
     * @return BattleLog
     */
    public static function createFromMovement(Movement $movement, $winner = null)
    {
        $battleLog = new self([
            'start_name' => $movement->start->display_name,
            'end_name' => $movement->end->display_name,
            'type' => static::TYPE_ATTACK,
            'winner' => $winner ?: static::WINNER_ATTACKER,
        ]);

        switch ($movement->type) {
            case Movement::TYPE_SCOUT:
                $battleLog->type = static::TYPE_SCOUT;
                break;
            case Movement::TYPE_OCCUPY:
                $battleLog->type = static::TYPE_OCCUPY;
                break;
        }

        $battleLog->attacker()->associate($movement->start->user_id);
        $battleLog->defender()->associate($movement->end->user_id);

        $battleLog->start()->associate($movement->start_id);
        $battleLog->end()->associate($movement->end_id);

        $battleLog->save();

        return $battleLog;
    }

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
     * Get the start.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function start()
    {
        return $this->belongsTo(Planet::class, 'start_id');
    }

    /**
     * Get the end.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function end()
    {
        return $this->belongsTo(Planet::class, 'end_id');
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
