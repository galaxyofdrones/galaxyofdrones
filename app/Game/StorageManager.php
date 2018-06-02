<?php

namespace Koodilab\Game;

use Illuminate\Contracts\Auth\Factory as Auth;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;

class StorageManager
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Constructor.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Has stock?
     *
     * @param resource $resource
     * @param int      $quantity
     * @param bool     $withoutStorage
     *
     * @return bool
     */
    public function hasStock(Resource $resource, $quantity, $withoutStorage = false)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $stock = $user->current->findStockByResource($resource);

        if ($withoutStorage || ! $user->current->isCapital()) {
            return $stock && $stock->hasQuantity($quantity);
        }

        $userResource = $user->resources->firstWhere('id', $resource->id);

        if ($stock && ! $userResource) {
            return $stock && $stock->hasQuantity($quantity);
        }

        if (! $stock && $userResource) {
            return $quantity <= $userResource->pivot->quantity;
        }

        $storageQuantity = $quantity - $stock->quantity;

        if ($storageQuantity > $userResource->pivot->quantity) {
            return false;
        }

        return true;
    }

    /**
     * Decrement the stock.
     *
     * @param resource $resource
     * @param int      $quantity
     * @param bool     $withoutStorage
     */
    public function decrementStock(Resource $resource, $quantity, $withoutStorage = false)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $stock = $user->current->findStockByResource($resource);

        if ($withoutStorage || ! $user->current->isCapital()) {
            $stock->decrementQuantity($quantity);
        } else {
            $userResource = $user->resources->firstWhere('id', $resource->id);

            if ($stock && ! $userResource) {
                $stock->decrementQuantity($quantity);
            } elseif (! $stock && $userResource) {
                $userResource->pivot->update([
                    'quantity' => max(0, $userResource->pivot->quantity - $quantity),
                ]);
            } else {
                if ($stock->hasQuantity($quantity)) {
                    $stock->decrementQuantity($quantity);
                } else {
                    $storageQuantity = $quantity - $stock->quantity;

                    $userResource->pivot->update([
                        'quantity' => max(0, $userResource->pivot->quantity - $storageQuantity),
                    ]);

                    $stock->decrementQuantity(
                        $quantity - $storageQuantity
                    );
                }
            }
        }
    }

    /**
     * Has population?
     *
     * @param Unit $unit
     * @param int  $quantity
     * @param bool $withoutStorage
     *
     * @return bool
     */
    public function hasPopulation(Unit $unit, $quantity, $withoutStorage = false)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $population = $user->current->findPopulationByUnit($unit);

        if ($withoutStorage || ! $user->current->isCapital()) {
            return $population && $population->hasQuantity($quantity);
        }

        $userUnit = $user->units->firstWhere('id', $unit->id);

        if ($population && ! $userUnit) {
            return $population && $population->hasQuantity($quantity);
        }

        if (! $population && $userUnit) {
            return $quantity <= $userUnit->pivot->quantity;
        }

        $storageQuantity = $quantity - $population->quantity;

        if ($storageQuantity > $userUnit->pivot->quantity) {
            return false;
        }

        return true;
    }

    /**
     * Decrement the population.
     *
     * @param Unit $unit
     * @param int  $quantity
     * @param bool $withoutStorage
     */
    public function decrementPopulation(Unit $unit, $quantity, $withoutStorage = false)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $population = $user->current->findPopulationByUnit($unit);

        if ($withoutStorage || ! $user->current->isCapital()) {
            $population->decrementQuantity($quantity);
        } else {
            $userUnit = $user->units->firstWhere('id', $unit->id);

            if ($population && ! $userUnit) {
                $population->decrementQuantity($quantity);
            } elseif (! $population && $userUnit) {
                $userUnit->pivot->update([
                    'quantity' => max(0, $userUnit->pivot->quantity - $quantity),
                ]);
            } else {
                if ($population->hasQuantity($quantity)) {
                    $population->decrementQuantity($quantity);
                } else {
                    $storageQuantity = $quantity - $population->quantity;

                    $userUnit->pivot->update([
                        'quantity' => max(0, $userUnit->pivot->quantity - $storageQuantity),
                    ]);

                    $population->decrementQuantity(
                        $quantity - $storageQuantity
                    );
                }
            }
        }
    }
}
