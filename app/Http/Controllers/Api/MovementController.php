<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Game\MovementManager;
use Koodilab\Game\StorageManager;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
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
     * @param Planet          $planet
     * @param MovementManager $movementManager
     * @param StorageManager  $storageManager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeScout(Planet $planet, MovementManager $movementManager, StorageManager $storageManager)
    {
        $this->authorize('hostile', $planet);

        $quantity = $this->quantity();
        $unit = Unit::findByType(Unit::TYPE_SCOUT);

        if (! $storageManager->hasPopulation(auth()->user()->current, $unit, $quantity)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($planet, $unit, $quantity, $movementManager) {
            $movementManager->createScout(
                $planet, $unit, $quantity
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Planet          $planet
     * @param MovementManager $movementManager
     * @param StorageManager  $storageManager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeAttack(Planet $planet, MovementManager $movementManager, StorageManager $storageManager)
    {
        $this->authorize('hostile', $planet);

        $quantities = $this->quantities();

        $units = Unit::findAllByIdsAndTypes($quantities->keys(), [
            Unit::TYPE_FIGHTER, Unit::TYPE_HEAVY_FIGHTER,
        ]);

        foreach ($units as $unit) {
            if (! $storageManager->hasPopulation(auth()->user()->current, $unit, $quantities->get($unit->id))) {
                throw new BadRequestHttpException();
            }
        }

        DB::transaction(function () use ($planet, $units, $quantities, $movementManager) {
            $movementManager->createAttack(
                $planet, $units, $quantities
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Planet          $planet
     * @param MovementManager $movementManager
     * @param StorageManager  $storageManager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeOccupy(Planet $planet, MovementManager $movementManager, StorageManager $storageManager)
    {
        $this->authorize('hostile', $planet);

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        if (! $user->canOccupy($planet)) {
            throw new BadRequestHttpException();
        }

        $unit = Unit::findByType(Unit::TYPE_SETTLER);

        if (! $storageManager->hasPopulation(auth()->user()->current, $unit, Planet::SETTLER_COUNT)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($planet, $unit, $movementManager) {
            $movementManager->createOccupy(
                $planet, $unit
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Planet          $planet
     * @param MovementManager $movementManager
     * @param StorageManager  $storageManager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeSupport(Planet $planet, MovementManager $movementManager, StorageManager $storageManager)
    {
        $this->authorize('friendly', $planet);

        $quantities = $this->quantities();
        $units = Unit::findAllByIds($quantities->keys());

        foreach ($units as $unit) {
            if (! $storageManager->hasPopulation(auth()->user()->current, $unit, $quantities->get($unit->id))) {
                throw new BadRequestHttpException();
            }
        }

        DB::transaction(function () use ($planet, $units, $quantities, $movementManager) {
            $movementManager->createSupport(
                $planet, $units, $quantities
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Planet          $planet
     * @param MovementManager $movementManager
     * @param StorageManager  $storageManager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeTransport(Planet $planet, MovementManager $movementManager, StorageManager $storageManager)
    {
        $this->authorize('friendly', $planet);

        $quantities = $this->quantities();
        $unit = Unit::findByType(Unit::TYPE_TRANSPORTER);

        $quantity = ceil(
            $quantities->sum() / $unit->capacity
        );

        if (! $storageManager->hasPopulation(auth()->user()->current, $unit, $quantity)) {
            throw new BadRequestHttpException();
        }

        $resources = Resource::findAllByIds($quantities->keys());

        foreach ($resources as $resource) {
            if (! $storageManager->hasStock(auth()->user()->current, $resource, $quantities->get($resource->id))) {
                throw new BadRequestHttpException();
            }
        }

        DB::transaction(function () use ($planet, $unit, $resources, $quantity, $quantities, $movementManager) {
            $movementManager->createTransport(
                $planet, $unit, $resources, $quantity, $quantities
            );
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Grid            $grid
     * @param MovementManager $movementManager
     * @param StorageManager  $storageManager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeTrade(Grid $grid, MovementManager $movementManager, StorageManager $storageManager)
    {
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_TRADER]);

        $quantities = $this->quantities();
        $unit = Unit::findByType(Unit::TYPE_TRANSPORTER);

        $quantity = ceil(
            $quantities->sum() / $unit->capacity
        );

        if (! $storageManager->hasPopulation(auth()->user()->current, $unit, $quantity)) {
            throw new BadRequestHttpException();
        }

        $resources = Resource::findAllByIds($quantities->keys());

        foreach ($resources as $resource) {
            if (! $storageManager->hasStock(auth()->user()->current, $resource, $quantities->get($resource->id), true)) {
                throw new BadRequestHttpException();
            }
        }

        $grid->building->applyModifiers([
            'level' => $grid->level,
        ]);

        DB::transaction(function () use ($grid, $unit, $resources, $quantity, $quantities, $movementManager) {
            if ($grid->planet->isCapital()) {
                $movementManager->createCapitalTrade(
                    $resources, $quantities
                );
            } else {
                $movementManager->createTrade(
                    $grid->building, $unit, $resources, $quantity, $quantities
                );
            }
        });
    }

    /**
     * Store a newly created movement in storage.
     *
     * @param Grid            $grid
     * @param MovementManager $movementManager
     * @param StorageManager  $storageManager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storePatrol(Grid $grid, MovementManager $movementManager, StorageManager $storageManager)
    {
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_TRADER]);

        $quantities = $this->quantities();
        $units = Unit::findAllByIds($quantities->keys());

        foreach ($units as $unit) {
            if (! $storageManager->hasPopulation(auth()->user()->current, $unit, $quantities->get($unit->id), true)) {
                throw new BadRequestHttpException();
            }
        }

        $grid->building->applyModifiers([
            'level' => $grid->level,
        ]);

        DB::transaction(function () use ($grid, $units, $quantities, $movementManager) {
            if ($grid->planet->isCapital()) {
                $movementManager->createCapitalPatrol(
                    $units, $quantities
                );
            } else {
                $movementManager->createPatrol(
                    $grid->building, $units, $quantities
                );
            }
        });
    }
}
