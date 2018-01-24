<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\Population;
use Koodilab\Models\Stock;
use Koodilab\Models\Unit;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MovementController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('player');
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Planet $planet
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeScout(Planet $planet)
    {
        $this->authorize('hostile', $planet);

        $quantity = $this->quantity();

        /** @var \Koodilab\Models\Population $population */
        $population = auth()->user()->current->findPopulationByUnit(
            Unit::findByType(Unit::TYPE_SCOUT)
        );

        if (!$population || !$population->hasQuantity($quantity)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($planet, $population, $quantity) {
            Movement::createScoutFrom(
                $planet, $population, $quantity
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Planet $planet
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeAttack(Planet $planet)
    {
        $this->authorize('hostile', $planet);

        $quantities = $this->quantities();

        $populations = auth()->user()->current->findPopulationsByUnitIds($quantities->keys())
            ->filter(function (Population $population) {
                return in_array($population->unit->type, [
                    Unit::TYPE_FIGHTER, Unit::TYPE_HEAVY_FIGHTER,
                ]);
            })
            ->each(function (Population $population) use ($quantities) {
                if (!$population->hasQuantity($quantities->get($population->unit_id))) {
                    throw new BadRequestHttpException();
                }
            });

        DB::transaction(function () use ($planet, $populations, $quantities) {
            Movement::createAttackFrom(
                $planet, $populations, $quantities
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Planet $planet
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeOccupy(Planet $planet)
    {
        $this->authorize('hostile', $planet);

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        if (!$user->canOccupy($planet)) {
            throw new BadRequestHttpException();
        }

        $population = $user->current->findPopulationByUnit(
            Unit::findByType(Unit::TYPE_SETTLER)
        );

        if (!$population || !$population->hasQuantity(Planet::SETTLER_COUNT)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($planet, $population) {
            Movement::createOccupyFrom(
                $planet, $population
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Planet $planet
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeSupport(Planet $planet)
    {
        $this->authorize('friendly', $planet);

        $quantities = $this->quantities();

        $populations = auth()->user()->current->findPopulationsByUnitIds($quantities->keys())
            ->each(function (Population $population) use ($quantities) {
                if (!$population->hasQuantity($quantities->get($population->unit_id))) {
                    throw new BadRequestHttpException();
                }
            });

        DB::transaction(function () use ($planet, $populations, $quantities) {
            Movement::createSupportFrom(
                $planet, $populations, $quantities
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Planet $planet
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeTransport(Planet $planet)
    {
        $this->authorize('friendly', $planet);

        $quantities = $this->quantities();

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        $population = $user->current->findPopulationByUnit(
            Unit::findByType(Unit::TYPE_TRANSPORTER)
        );

        $quantity = ceil(
            $quantities->sum() / $population->unit->capacity
        );

        if (!$population || !$population->hasQuantity($quantity)) {
            throw new BadRequestHttpException();
        }

        $stocks = $user->current->findStocksByResourceIds($quantities->keys())
            ->each(function (Stock $stock) use ($quantities, $user) {
                if (!$stock->setRelation('planet', $user->current)->hasQuantity($quantities->get($stock->resource_id))) {
                    throw new BadRequestHttpException();
                }
            });

        DB::transaction(function () use ($planet, $population, $stocks, $quantity, $quantities) {
            Movement::createTransportFrom(
                $planet, $population, $stocks, $quantity, $quantities
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeTrade()
    {
        $quantities = $this->quantities();

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        /** @var Building $building */
        $building = $user->current->findBuildings()
            ->firstWhere('type', Building::TYPE_TRADER);

        if (!$building) {
            throw new BadRequestHttpException();
        }

        $population = $user->current->findPopulationByUnit(
            Unit::findByType(Unit::TYPE_TRANSPORTER)
        );

        $quantity = ceil(
            $quantities->sum() / $population->unit->capacity
        );

        if (!$population || !$population->hasQuantity($quantity)) {
            throw new BadRequestHttpException();
        }

        /** @var Stock[] $stocks */
        $stocks = $user->current->findStocksByResourceIds($quantities->keys())
            ->each(function (Stock $stock) use ($quantities, $user) {
                if (!$stock->setRelation('planet', $user->current)->hasQuantity($quantities->get($stock->resource_id))) {
                    throw new BadRequestHttpException();
                }
            });

        DB::transaction(function () use ($user, $building, $population, $stocks, $quantity, $quantities) {
            if ($user->capital_id == $user->current_id) {
                foreach ($stocks as $stock) {
                    $stock->decrementQuantity(
                        $quantities->get($stock->resource_id)
                    );

                    $userResource = $user->resources->firstWhere('id', $stock->resource_id);

                    if (!$userResource) {
                        $user->resources()->attach($stock->resource_id, [
                            'is_researched' => false,
                            'quantity' => $quantities->get($stock->resource_id),
                        ]);
                    } else {
                        $userResource->pivot->update([
                            'quantity' => $userResource->pivot->quantity + $quantities->get($stock->resource_id),
                        ]);
                    }
                }

                event(
                    new PlanetUpdated($user->capital_id)
                );
            } else {
                Movement::createTradeFrom(
                    $building, $population, $stocks, $quantity, $quantities
                );
            }
        });
    }
}
