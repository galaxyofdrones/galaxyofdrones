<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as BaseCollection;
use Koodilab\Contracts\Battle\Simulator;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Events\UserUpdated;
use Koodilab\Jobs\Move as MoveJob;

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
        Queries\FindResourcesOrderBySortOrder,
        Queries\FindUnitsOrderBySortOrder,
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
     * The transport type.
     *
     * @var int
     */
    const TYPE_TRADE = 5;

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
     * Create scout from.
     *
     * @param Planet     $planet
     * @param Population $population
     * @param int        $quantity
     *
     * @return static
     */
    public static function createScoutFrom(Planet $planet, Population $population, $quantity)
    {
        /** @var User $user */
        $user = auth()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $population->unit->speed
        );

        $movement = static::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => static::TYPE_SCOUT,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        $population->decrementQuantity($quantity);

        $movement->units()->attach($population->unit->id, [
            'quantity' => $quantity,
        ]);

        return static::createFrom($movement);
    }

    /**
     * Create attack from.
     *
     * @param Planet                  $planet
     * @param Collection|Population[] $populations
     * @param BaseCollection          $quantities
     *
     * @return static
     */
    public static function createAttackFrom(Planet $planet, Collection $populations, BaseCollection $quantities)
    {
        /** @var User $user */
        $user = auth()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $populations->min('unit.speed')
        );

        $movement = static::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => static::TYPE_ATTACK,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        foreach ($populations as $population) {
            $population->decrementQuantity(
                $quantities->get($population->unit_id)
            );

            $movement->units()->attach($population->unit_id, [
                'quantity' => $quantities->get($population->unit_id),
            ]);
        }

        return static::createFrom($movement);
    }

    /**
     * Create occupy from.
     *
     * @param Planet     $planet
     * @param Population $population
     *
     * @return static
     */
    public static function createOccupyFrom(Planet $planet, Population $population)
    {
        /** @var User $user */
        $user = auth()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $population->unit->speed
        );

        $movement = static::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => static::TYPE_OCCUPY,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        $population->decrementQuantity(Planet::SETTLER_COUNT);

        $movement->units()->attach($population->unit->id, [
            'quantity' => Planet::SETTLER_COUNT,
        ]);

        return static::createFrom($movement);
    }

    /**
     * Create support from.
     *
     * @param Planet                  $planet
     * @param Collection|Population[] $populations
     * @param BaseCollection          $quantities
     *
     * @return static
     */
    public static function createSupportFrom(Planet $planet, Collection $populations, BaseCollection $quantities)
    {
        /** @var User $user */
        $user = auth()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $populations->min('unit.speed')
        );

        $movement = static::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => static::TYPE_SUPPORT,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        foreach ($populations as $population) {
            $population->decrementQuantity(
                $quantities->get($population->unit_id)
            );

            $movement->units()->attach($population->unit_id, [
                'quantity' => $quantities->get($population->unit_id),
            ]);
        }

        return static::createFrom($movement);
    }

    /**
     * Create transport from.
     *
     * @param Planet             $planet
     * @param Population         $population
     * @param Collection|Stock[] $stocks
     * @param int                $quantity
     * @param BaseCollection     $quantities
     *
     * @return static
     */
    public static function createTransportFrom(Planet $planet, Population $population, Collection $stocks, $quantity, BaseCollection $quantities)
    {
        /** @var User $user */
        $user = auth()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $population->unit->speed
        );

        $movement = static::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => static::TYPE_TRANSPORT,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        return static::createTransportOrTradeFrom(
            $movement, $population, $stocks, $quantity, $quantities
        );
    }

    /**
     * Create trade from.
     *
     * @param Building           $building
     * @param Population         $population
     * @param Collection|Stock[] $stocks
     * @param int                $quantity
     * @param BaseCollection     $quantities
     *
     * @return static
     */
    public static function createTradeFrom(Building $building, Population $population, Collection $stocks, $quantity, BaseCollection $quantities)
    {
        /** @var User $user */
        $user = auth()->user();

        $travelTime = round(
            $user->capital->travelTimeTo($user->current) / $population->unit->speed * (1 - $building->trade_time_bonus)
        );

        $movement = static::create([
            'start_id' => $user->current_id,
            'end_id' => $user->capital_id,
            'user_id' => $user->id,
            'type' => static::TYPE_TRADE,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        return static::createTransportOrTradeFrom(
            $movement, $population, $stocks, $quantity, $quantities
        );
    }

    /**
     * Create transport or trade from.
     *
     * @param Movement           $movement
     * @param Population         $population
     * @param Collection|Stock[] $stocks
     * @param int                $quantity
     * @param BaseCollection     $quantities
     *
     * @return static
     */
    protected static function createTransportOrTradeFrom(self $movement, Population $population, Collection $stocks, $quantity, BaseCollection $quantities)
    {
        $population->decrementQuantity($quantity);

        $movement->units()->attach($population->unit->id, [
            'quantity' => $quantity,
        ]);

        foreach ($stocks as $stock) {
            $stock->decrementQuantity(
                $quantities->get($stock->resource_id)
            );

            $movement->resources()->attach($stock->resource_id, [
                'quantity' => $quantities->get($stock->resource_id),
            ]);
        }

        return static::createFrom($movement);
    }

    /**
     * Create from.
     *
     * @param Movement $movement
     *
     * @return static
     */
    protected static function createFrom(self $movement)
    {
        dispatch(
            (new MoveJob($movement->id))->delay($movement->remaining ?: null)
        );

        event(
            new PlanetUpdated($movement->start_id)
        );

        event(
            new PlanetUpdated($movement->end_id)
        );

        return $movement;
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
            case static::TYPE_TRADE:
                $this->finishTrade();
                break;
        }

        $this->delete();

        event(
            new PlanetUpdated($this->start_id)
        );

        event(
            new PlanetUpdated($this->end_id)
        );
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
     * Handle the transport movement.
     */
    protected function finishTransport()
    {
        $this->transferResources();
        $this->returnMovement();
    }

    /**
     * Handle the trade movement.
     */
    protected function finishTrade()
    {
        $this->transferMissionResources();
        $this->returnMovement();

        event(
            new UserUpdated($this->user_id)
        );
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
     * Transfer the mission resources.
     */
    protected function transferMissionResources()
    {
        foreach ($this->resources as $resource) {
            $userResource = $this->user->resources->firstWhere('id', $resource->id);

            if (!$userResource) {
                $this->user->resources()->attach($resource->id, [
                    'is_researched' => false,
                    'quantity' => $resource->pivot->quantity,
                ]);
            } else {
                $userResource->pivot->update([
                    'quantity' => $userResource->pivot->quantity + $resource->pivot->quantity,
                ]);
            }
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

            $returnMovement = static::create([
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

            dispatch(
                (new MoveJob($returnMovement->id))->delay($returnMovement->remaining ?: null)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        $this->delete();
    }
}
