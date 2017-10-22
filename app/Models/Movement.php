<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Battle\Simulator;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Jobs\Move;

/**
 * Movement.
 *
 * @property int $id
 * @property int $start_id
 * @property int $end_id
 * @property int $user_id
 * @property int $type
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property Planet $end
 * @property int $remaining
 * @property \Illuminate\Database\Eloquent\Collection|resource[] $resources
 * @property Planet $start
 * @property \Illuminate\Database\Eloquent\Collection|Unit[] $units
 * @property User $user
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
class Movement extends Model implements TimeableContract
{
    use Behaviors\Timeable,
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
     * {@inheritdoc}
     */
    protected $dates = [
        'ended_at',
    ];

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

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        switch ($this->type) {
            case static::TYPE_SCOUT:
                $this->finishScout();
                break;
            case static::TYPE_ATTACK:
                $this->finishAttack();
                break;
            case static::TYPE_OCCUPY:
                $this->finishOccupy();
                break;
            case static::TYPE_SUPPORT:
                $this->finishSupport();
                break;
            case static::TYPE_TRANSPORT:
                $this->finishTransport();
                break;
        }

        $this->delete();

        event(
            new PlanetUpdated($this->start_id)
        );

        event(
            new PlanetUpdated($this->end_id)
        );

        return true;
    }

    /**
     * Finish the scout.
     */
    protected function finishScout()
    {
        /** @var BattleLog $battleLog */
        $battleLog = app(Simulator::class)->scout($this);

        $this->returnMovement($battleLog->attackerUnits);
    }

    /**
     * Handle the attack.
     */
    protected function finishAttack()
    {
        /** @var BattleLog $battleLog */
        $battleLog = app(Simulator::class)->attack($this);

        $this->returnMovement($battleLog->attackerUnits, $battleLog->resources);
    }

    /**
     * Handle the occupy.
     */
    protected function finishOccupy()
    {
        /** @var BattleLog $battleLog */
        $battleLog = app(Simulator::class)->occupy($this);

        if ($battleLog->winner == BattleLog::WINNER_ATTACKER) {
            if (!$battleLog->attacker->occupy($battleLog->end)) {
                $this->returnMovement($battleLog->attackerUnits);
            }
        }
    }

    /**
     * Handle the support movement.
     */
    protected function finishSupport()
    {
        $this->transferUnits();
        $this->transferResources();
    }

    /**
     * Handle the resource movement.
     */
    protected function finishTransport()
    {
        $this->transferResources();
        $this->returnMovement();
    }

    /**
     * Transfer the units.
     */
    protected function transferUnits()
    {
        foreach ($this->units as $unit) {
            /** @var Population $population */
            $population = $this->end->populations()->firstOrNew([
                'unit_id' => $unit->id,
            ]);

            $population->setRelation('planet', $this->end)
                ->setRelation('unit', $unit)
                ->incrementQuantity($unit->pivot->quantity);
        }
    }

    /**
     * Transfer the resources.
     */
    protected function transferResources()
    {
        foreach ($this->resources as $resource) {
            /** @var Stock $stock */
            $stock = $this->end->stocks()->firstOrNew([
                'resource_id' => $resource->id,
            ]);

            $stock->setRelation('planet', $this->end)
                ->incrementQuantity($resource->pivot->quantity);
        }
    }

    /**
     * Start a return movement.
     *
     * @param Collection|Unit[]     $units
     * @param Collection|resource[] $resources
     */
    protected function returnMovement(Collection $units = null, Collection $resources = null)
    {
        $units = $units ?: $this->units;

        if ($units->sum('pivot.quantity') > $units->sum('pivot.losses')) {
            $travelTime = $this->ended_at->diffInSeconds($this->created_at);

            $returnMovement = self::create([
                'start_id' => $this->end_id,
                'end_id' => $this->start_id,
                'user_id' => $this->user_id,
                'type' => static::TYPE_SUPPORT,
                'ended_at' => Carbon::now()->addSeconds($travelTime),
            ]);

            foreach ($units as $unit) {
                $quantity = $unit->pivot->quantity - $unit->pivot->losses;

                if ($quantity) {
                    $returnMovement->units()->attach($unit->id, [
                        'quantity' => $quantity,
                    ]);
                }
            }

            if ($resources) {
                foreach ($resources as $resource) {
                    $quantity = $resource->pivot->losses;

                    if ($quantity) {
                        $returnMovement->resources()->attach($resource->id, [
                            'quantity' => $quantity,
                        ]);
                    }
                }
            }

            dispatch((new Move($returnMovement->id))->delay($returnMovement->remaining));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
    }
}
