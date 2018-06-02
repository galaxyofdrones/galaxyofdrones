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

        $stock = $user->current->findStockByResource(
            $resource
        );

        if (! $stock) {
            return false;
        }

        if ($quantity > $stock->quantity) {
            if (! $user->current->isCapital()) {
                return false;
            }

            $required = $quantity - $stock->quantity;
            $userResource = $user->resources->firstWhere('id', $resource->id);

            if ($required > $userResource->pivot->quantity) {
                return false;
            }
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

        $stock = $user->current->findStockByResource(
            $resource
        );

        if (! $stock) {
            return;
        }

        if ($quantity > $stock->quantity && $user->current->isCapital()) {
            $required = $quantity - $stock->quantity;
            $userResource = $user->resources->firstWhere('id', $resource->id);

            $userResource->pivot->update([
                'quantity' => max(0, $userResource->pivot->quantity - $required),
            ]);

            $stock->decrementQuantity(
                $quantity - $required
            );
        } else {
            $stock->decrementQuantity($quantity);
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

        $population = $user->current->findPopulationByUnit(
            $unit
        );

        if (! $population) {
            return false;
        }

        if ($quantity > $population->quantity) {
            if (! $user->current->isCapital()) {
                return false;
            }

            $required = $quantity - $population->quantity;
            $userUnit = $user->units->firstWhere('id', $unit->id);

            if ($required > $userUnit->pivot->quantity) {
                return true;
            }
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

        $population = $user->current->findPopulationByUnit(
            $unit
        );

        if (! $population) {
            return;
        }

        if ($quantity > $population->quantity && $user->current->isCapital()) {
            $required = $quantity - $population->quantity;
            $userUnit = $user->units->firstWhere('id', $unit->id);

            $userUnit->pivot->update([
                'quantity' => max(0, $userUnit->pivot->quantity - $required),
            ]);

            $population->decrementQuantity(
                $quantity - $required
            );
        } else {
            $population->decrementQuantity($quantity);
        }
    }
}
