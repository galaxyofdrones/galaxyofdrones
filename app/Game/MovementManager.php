<?php

namespace Koodilab\Game;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Events\Dispatcher as Event;
use Illuminate\Support\Collection as BaseCollection;
use Koodilab\Contracts\Battle\Simulator as SimulatorContract;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Events\UserUpdated;
use Koodilab\Jobs\Move as MoveJob;
use Koodilab\Models\BattleLog;
use Koodilab\Models\Building;
use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\Population;
use Koodilab\Models\Unit;

class MovementManager
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * The bus instance.
     *
     * @var Bus
     */
    protected $bus;

    /**
     * The event instance.
     *
     * @var Event
     */
    protected $event;

    /**
     * The simulator instance.
     *
     * @var SimulatorContract
     */
    protected $simulator;

    /**
     * The storage manager instance.
     *
     * @var StorageManager
     */
    protected $storageManager;

    /**
     * Constructor.
     *
     * @param Auth              $auth
     * @param Bus               $bus
     * @param Event             $event
     * @param SimulatorContract $simulator
     * @param StorageManager    $storageManager
     */
    public function __construct(Auth $auth, Bus $bus, Event $event, SimulatorContract $simulator, StorageManager $storageManager)
    {
        $this->auth = $auth;
        $this->bus = $bus;
        $this->event = $event;
        $this->simulator = $simulator;
        $this->storageManager = $storageManager;
    }

    /**
     * Create scout.
     *
     * @param Planet $planet
     * @param Unit   $unit
     * @param int    $quantity
     *
     * @return Movement
     */
    public function createScout(Planet $planet, Unit $unit, $quantity)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $unit->speed
        );

        $movement = Movement::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => Movement::TYPE_SCOUT,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        $this->storageManager->decrementPopulation(
            $user->current, $unit, $quantity
        );

        $movement->units()->attach($unit->id, [
            'quantity' => $quantity,
        ]);

        $this->dispatchJobAndEvents($movement);

        return $movement;
    }

    /**
     * Create attack.
     *
     * @param Planet            $planet
     * @param Collection|Unit[] $units
     * @param BaseCollection    $quantities
     *
     * @return Movement
     */
    public function createAttack(Planet $planet, Collection $units, BaseCollection $quantities)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $units->min('speed')
        );

        $movement = Movement::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => Movement::TYPE_ATTACK,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        foreach ($units as $unit) {
            $this->storageManager->decrementPopulation(
                $user->current, $unit, $quantities->get($unit->id)
            );

            $movement->units()->attach($unit->id, [
                'quantity' => $quantities->get($unit->id),
            ]);
        }

        $this->dispatchJobAndEvents($movement);

        return $movement;
    }

    /**
     * Create occupy.
     *
     * @param Planet $planet
     * @param Unit   $unit
     *
     * @return Movement
     */
    public function createOccupy(Planet $planet, Unit $unit)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $unit->speed
        );

        $movement = Movement::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => Movement::TYPE_OCCUPY,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        $this->storageManager->decrementPopulation(
            $user->current, $unit, Planet::SETTLER_COUNT
        );

        $movement->units()->attach($unit->id, [
            'quantity' => Planet::SETTLER_COUNT,
        ]);

        $this->dispatchJobAndEvents($movement);

        return $movement;
    }

    /**
     * Create support.
     *
     * @param Planet            $planet
     * @param Collection|Unit[] $units
     * @param BaseCollection    $quantities
     *
     * @return Movement
     */
    public function createSupport(Planet $planet, Collection $units, BaseCollection $quantities)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $units->min('speed')
        );

        $movement = Movement::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => Movement::TYPE_SUPPORT,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        return $this->createSupportOrPatrol(
            $movement, $units, $quantities
        );
    }

    /**
     * Create transport.
     *
     * @param Planet                                 $planet
     * @param Unit                                   $unit
     * @param Collection|\Koodilab\Models\Resource[] $resources
     * @param int                                    $quantity
     * @param BaseCollection                         $quantities
     *
     * @return Movement
     */
    public function createTransport(Planet $planet, Unit $unit, Collection $resources, $quantity, BaseCollection $quantities)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $travelTime = round(
            $planet->travelTimeTo($user->current) / $unit->speed
        );

        $movement = Movement::create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'user_id' => $user->id,
            'type' => Movement::TYPE_TRANSPORT,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        return $this->createTransportOrTrade(
            $movement, $unit, $resources, $quantity, $quantities
        );
    }

    /**
     * Create trade.
     *
     * @param Building                               $building
     * @param Unit                                   $unit
     * @param Collection|\Koodilab\Models\Resource[] $resources
     * @param int                                    $quantity
     * @param BaseCollection                         $quantities
     *
     * @return Movement
     */
    public function createTrade(Building $building, Unit $unit, Collection $resources, $quantity, BaseCollection $quantities)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $travelTime = round(
            $user->capital->travelTimeTo($user->current) / $unit->speed * (1 - $building->trade_time_bonus)
        );

        $movement = Movement::create([
            'start_id' => $user->current_id,
            'end_id' => $user->capital_id,
            'user_id' => $user->id,
            'type' => Movement::TYPE_TRADE,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        return $this->createTransportOrTrade(
            $movement, $unit, $resources, $quantity, $quantities
        );
    }

    /**
     * Create capital trade.
     *
     * @param Collection|\Koodilab\Models\Resource[] $resources
     * @param BaseCollection                         $quantities
     */
    public function createCapitalTrade(Collection $resources, BaseCollection $quantities)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        foreach ($resources as $resource) {
            $this->storageManager->decrementStock(
                $user->capital, $resource, $quantities->get($resource->id), true
            );

            $userResource = $user->resources->firstWhere('id', $resource->id);

            if (! $userResource) {
                $user->resources()->attach($resource->id, [
                    'is_researched' => false,
                    'quantity' => $quantities->get($resource->id),
                ]);
            } else {
                $userResource->pivot->update([
                    'quantity' => $userResource->pivot->quantity + $quantities->get($resource->id),
                ]);
            }
        }

        $this->event->dispatch(
            new PlanetUpdated($user->capital_id)
        );
    }

    /**
     * Create patrol.
     *
     * @param Building          $building
     * @param Collection|Unit[] $units
     * @param BaseCollection    $quantities
     *
     * @return Movement
     */
    public function createPatrol(Building $building, Collection $units, BaseCollection $quantities)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $travelTime = round(
            $user->capital->travelTimeTo($user->current) / $units->min('speed') * (1 - $building->trade_time_bonus)
        );

        $movement = Movement::create([
            'start_id' => $user->current_id,
            'end_id' => $user->capital_id,
            'user_id' => $user->id,
            'type' => Movement::TYPE_PATROL,
            'ended_at' => Carbon::now()->addSeconds($travelTime),
        ]);

        return $this->createSupportOrPatrol(
            $movement, $units, $quantities
        );
    }

    /**
     * Create capital patrol.
     *
     * @param Collection|Unit[] $units
     * @param BaseCollection    $quantities
     */
    public function createCapitalPatrol(Collection $units, BaseCollection $quantities)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        foreach ($units as $unit) {
            $this->storageManager->decrementPopulation(
                $user->capital, $unit, $quantities->get($unit->id), true
            );

            $userUnit = $user->units->firstWhere('id', $unit->id);

            if (! $userUnit) {
                $user->resources()->attach($unit->id, [
                    'is_researched' => false,
                    'quantity' => $quantities->get($unit->id),
                ]);
            } else {
                $userUnit->pivot->update([
                    'quantity' => $userUnit->pivot->quantity + $quantities->get($unit->id),
                ]);
            }
        }

        $this->event->dispatch(
            new PlanetUpdated($user->capital_id)
        );
    }

    /**
     * Finish.
     *
     * @param Movement $movement
     */
    public function finish(Movement $movement)
    {
        switch ($movement->type) {
            case Movement::TYPE_SCOUT:
                $this->finishScout($movement);
                break;
            case Movement::TYPE_ATTACK:
                $this->finishAttack($movement);
                break;
            case Movement::TYPE_OCCUPY:
                $this->finishOccupy($movement);
                break;
            case Movement::TYPE_SUPPORT:
                $this->finishSupport($movement);
                break;
            case Movement::TYPE_TRANSPORT:
                $this->finishTransport($movement);
                break;
            case Movement::TYPE_TRADE:
                $this->finishTrade($movement);
                break;
            case Movement::TYPE_PATROL:
                $this->finishPatrol($movement);
                break;
        }

        $movement->delete();

        $this->dispatchEvents($movement);
    }

    /**
     * Finish the scout.
     *
     * @param Movement $movement
     */
    protected function finishScout(Movement $movement)
    {
        if ($movement->end->hasShield() || $movement->user_id == $movement->end->user_id) {
            $this->returnMovement($movement);
        } else {
            /** @var BattleLog $battleLog */
            $battleLog = $this->simulator->scout($movement);

            $this->returnMovement(
                $movement, $battleLog->attackerUnits
            );
        }
    }

    /**
     * Finish the attack.
     *
     * @param Movement $movement
     */
    protected function finishAttack(Movement $movement)
    {
        if ($movement->end->hasShield() || $movement->user_id == $movement->end->user_id) {
            $this->returnMovement($movement);
        } else {
            /** @var BattleLog $battleLog */
            $battleLog = $this->simulator->attack($movement);

            $this->returnMovement(
                $movement, $battleLog->attackerUnits, $battleLog->resources
            );
        }
    }

    /**
     * Finish the occupy.
     *
     * @param Movement $movement
     */
    protected function finishOccupy(Movement $movement)
    {
        if ($movement->end->hasShield() || $movement->user_id == $movement->end->user_id) {
            $this->returnMovement($movement);
        } else {
            /** @var BattleLog $battleLog */
            $battleLog = $this->simulator->occupy($movement);

            if ($battleLog->winner == BattleLog::WINNER_ATTACKER) {
                if (! $battleLog->attacker->occupy($battleLog->end)) {
                    $this->returnMovement(
                        $movement, $battleLog->attackerUnits
                    );
                }
            }
        }
    }

    /**
     * Finish the support.
     *
     * @param Movement $movement
     */
    protected function finishSupport(Movement $movement)
    {
        if ($movement->end->isCapital()) {
            $this->transferPatrolUnits($movement);
            $this->transferTradeResources($movement);
        } else {
            $this->transferUnits($movement);
            $this->transferResources($movement);
        }
    }

    /**
     * Finish the transport movement.
     *
     * @param Movement $movement
     */
    protected function finishTransport(Movement $movement)
    {
        $this->transferResources($movement);
        $this->returnMovement($movement);
    }

    /**
     * Finish the trade movement.
     *
     * @param Movement $movement
     */
    protected function finishTrade(Movement $movement)
    {
        $this->transferTradeResources($movement);
        $this->returnMovement($movement);

        $this->event->dispatch(
            new UserUpdated($movement->user_id)
        );
    }

    /**
     * Finish the patrol movement.
     *
     * @param Movement $movement
     */
    protected function finishPatrol(Movement $movement)
    {
        $this->transferPatrolUnits($movement);

        $this->event->dispatch(
            new UserUpdated($movement->user_id)
        );
    }

    /**
     * Create support or patrol.
     *
     * @param Movement          $movement
     * @param Collection|Unit[] $units
     * @param BaseCollection    $quantities
     *
     * @return Movement
     */
    protected function createSupportOrPatrol(Movement $movement, Collection $units, BaseCollection $quantities)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        foreach ($units as $unit) {
            $this->storageManager->decrementPopulation(
                $user->current, $unit, $quantities->get($unit->id)
            );

            $movement->units()->attach($unit->id, [
                'quantity' => $quantities->get($unit->id),
            ]);
        }

        $this->dispatchJobAndEvents($movement);

        return $movement;
    }

    /**
     * Create transport or trade.
     *
     * @param Movement                               $movement
     * @param Unit                                   $unit
     * @param Collection|\Koodilab\Models\Resource[] $resources
     * @param int                                    $quantity
     * @param BaseCollection                         $quantities
     *
     * @return Movement
     */
    protected function createTransportOrTrade(Movement $movement, Unit $unit, Collection $resources, $quantity, BaseCollection $quantities)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $this->storageManager->decrementPopulation(
            $user->current, $unit, $quantity
        );

        $movement->units()->attach($unit->id, [
            'quantity' => $quantity,
        ]);

        foreach ($resources as $resource) {
            $this->storageManager->decrementStock(
                $user->current, $resource, $quantities->get($resource->id)
            );

            $movement->resources()->attach($resource->id, [
                'quantity' => $quantities->get($resource->id),
            ]);
        }

        $this->dispatchJobAndEvents($movement);

        return $movement;
    }

    /**
     * Transfer the units.
     *
     * @param Movement $movement
     */
    protected function transferUnits(Movement $movement)
    {
        foreach ($movement->units as $unit) {
            /** @var Population $population */
            $population = $movement->end->populations()->firstOrNew([
                'unit_id' => $unit->id,
            ]);

            $population->setRelation('planet', $movement->end)
                ->setRelation('unit', $unit)
                ->incrementQuantity($unit->pivot->quantity);
        }
    }

    /**
     * Transfer the patrol units.
     *
     * @param Movement $movement
     */
    protected function transferPatrolUnits(Movement $movement)
    {
        foreach ($movement->units as $unit) {
            $userUnit = $movement->user->units->firstWhere('id', $unit->id);

            if (! $userUnit) {
                $movement->user->units()->attach($unit->id, [
                    'is_researched' => false,
                    'quantity' => $unit->pivot->quantity,
                ]);
            } else {
                $userUnit->pivot->update([
                    'quantity' => $userUnit->pivot->quantity + $unit->pivot->quantity,
                ]);
            }
        }
    }

    /**
     * Transfer the resources.
     *
     * @param Movement $movement
     */
    protected function transferResources(Movement $movement)
    {
        foreach ($movement->resources as $resource) {
            /** @var \Koodilab\Models\Stock $stock */
            $stock = $movement->end->stocks()->firstOrNew([
                'resource_id' => $resource->id,
            ]);

            $stock->setRelation('planet', $movement->end)
                ->incrementQuantity($resource->pivot->quantity);
        }
    }

    /**
     * Transfer the trade resources.
     *
     * @param Movement $movement
     */
    protected function transferTradeResources(Movement $movement)
    {
        foreach ($movement->resources as $resource) {
            $userResource = $movement->user->resources->firstWhere('id', $resource->id);

            if (! $userResource) {
                $movement->user->resources()->attach($resource->id, [
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
     * @param Movement                               $movement
     * @param Collection|\Koodilab\Models\Unit[]     $units
     * @param Collection|\Koodilab\Models\Resource[] $resources
     */
    protected function returnMovement(Movement $movement, Collection $units = null, Collection $resources = null)
    {
        $units = $units ?: $movement->units;

        if ($units->sum('pivot.quantity') > $units->sum('pivot.losses')) {
            $travelTime = $movement->ended_at->diffInSeconds($movement->created_at);

            $returnMovement = Movement::create([
                'start_id' => $movement->end_id,
                'end_id' => $movement->start_id,
                'user_id' => $movement->user_id,
                'type' => Movement::TYPE_SUPPORT,
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

            $this->dispatchJob($returnMovement);
        }
    }

    /**
     * Dispatch the job and events.
     *
     * @param Movement $movement
     */
    protected function dispatchJobAndEvents(Movement $movement)
    {
        $this->dispatchJob($movement);
        $this->dispatchEvents($movement);
    }

    /**
     * Dispatch the job.
     *
     * @param Movement $movement
     */
    protected function dispatchJob(Movement $movement)
    {
        $this->bus->dispatch(
            (new MoveJob($movement->id))->delay($movement->remaining)
        );
    }

    /**
     * Dispatch the events.
     *
     * @param Movement $movement
     */
    protected function dispatchEvents(Movement $movement)
    {
        $this->event->dispatch(
            new PlanetUpdated($movement->start_id)
        );

        $this->event->dispatch(
            new PlanetUpdated($movement->end_id)
        );

        if ($movement->end->user_id) {
            $this->event->dispatch(
                new UserUpdated($movement->end->user_id)
            );
        }
    }
}
