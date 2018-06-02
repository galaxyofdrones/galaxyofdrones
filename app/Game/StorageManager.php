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
     *
     * @return bool
     */
    public function hasStock(Resource $resource, $quantity)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $userResource = $user->resources->firstWhere('id', $resource->id);

        if (! $userResource) {
            return false;
        }

        $stock = $user->current->findStockByResource($resource);

        if (! $user->current->isCapital()) {
            return $stock && $stock->hasQuantity($quantity);
        }

        if (! $stock) {
            return $quantity <= $userResource->pivot->quantity;
        }

        $required = $quantity - $stock->quantity;

        if ($required > $userResource->pivot->quantity) {
            return false;
        }

        return true;
    }

    /**
     * Decrement the stock.
     *
     * @param resource $resource
     * @param int      $quantity
     */
    public function decrementStock(Resource $resource, $quantity)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $stock = $user->current->findStockByResource($resource);

        if (! $user->current->isCapital()) {
            $stock->decrementQuantity($quantity);
        } else {
            $userResource = $user->resources->firstWhere('id', $resource->id);

            if (! $stock) {
                $userResource->pivot->update([
                    'quantity' => max(0, $userResource->pivot->quantity - $quantity),
                ]);
            } else {
                $required = $quantity - $stock->quantity;

                $userResource->pivot->update([
                    'quantity' => max(0, $userResource->pivot->quantity - $required),
                ]);

                $stock->decrementQuantity(
                    $quantity - $required
                );
            }
        }
    }

    /**
     * Has population?
     *
     * @param Unit $unit
     * @param $quantity
     *
     * @return bool
     */
    public function hasPopulation(Unit $unit, $quantity)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $userUnit = $user->units->firstWhere('id', $unit->id);

        if (! $userUnit) {
            return false;
        }

        $population = $user->current->findPopulationByUnit($unit);

        if (! $user->current->isCapital()) {
            return $population && $population->hasQuantity($quantity);
        }

        if (! $population) {
            return $quantity <= $userUnit->pivot->quantity;
        }

        $required = $quantity - $population->quantity;

        if ($required > $userUnit->pivot->quantity) {
            return false;
        }

        return true;
    }

    /**
     * Decrement the population.
     *
     * @param Unit $unit
     * @param int  $quantity
     */
    public function decrementPopulation(Unit $unit, $quantity)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $population = $user->current->findPopulationByUnit($unit);

        if (! $user->current->isCapital()) {
            $population->decrementQuantity($quantity);
        } else {
            $userUnit = $user->units->firstWhere('id', $unit->id);

            if (! $population) {
                $userUnit->pivot->update([
                    'quantity' => max(0, $userUnit->pivot->quantity - $quantity),
                ]);
            } else {
                $required = $quantity - $population->quantity;

                $userUnit->pivot->update([
                    'quantity' => max(0, $userUnit->pivot->quantity - $required),
                ]);

                $population->decrementQuantity(
                    $quantity - $required
                );
            }
        }
    }
}
