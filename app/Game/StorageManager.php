<?php

namespace Koodilab\Game;

use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;

class StorageManager
{
    /**
     * Has stock?
     *
     * @param Planet   $planet
     * @param resource $resource
     * @param int      $quantity
     * @param bool     $withoutStorage
     *
     * @return bool
     */
    public function hasStock(Planet $planet, Resource $resource, $quantity, $withoutStorage = false)
    {
        $stock = $planet->findStockByResource($resource);

        if ($withoutStorage || ! $planet->isCapital()) {
            return $stock && $stock->hasQuantity($quantity);
        }

        $userResource = $planet->user->resources->firstWhere('id', $resource->id);

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
     * @param Planet   $planet
     * @param resource $resource
     * @param int      $quantity
     * @param bool     $withoutStorage
     */
    public function decrementStock(Planet $planet, Resource $resource, $quantity, $withoutStorage = false)
    {
        $stock = $planet->findStockByResource($resource);

        if ($withoutStorage || ! $planet->isCapital()) {
            $stock->decrementQuantity($quantity);
        } else {
            $userResource = $planet->user->resources->firstWhere('id', $resource->id);

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
     * @param Planet $planet
     * @param Unit   $unit
     * @param int    $quantity
     * @param bool   $withoutStorage
     *
     * @return bool
     */
    public function hasPopulation(Planet $planet, Unit $unit, $quantity, $withoutStorage = false)
    {
        $population = $planet->findPopulationByUnit($unit);

        if ($withoutStorage || ! $planet->isCapital()) {
            return $population && $population->hasQuantity($quantity);
        }

        $userUnit = $planet->user->units->firstWhere('id', $unit->id);

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
     * @param Planet $planet
     * @param Unit   $unit
     * @param int    $quantity
     * @param bool   $withoutStorage
     */
    public function decrementPopulation(Planet $planet, Unit $unit, $quantity, $withoutStorage = false)
    {
        $population = $planet->findPopulationByUnit($unit);

        if ($withoutStorage || ! $planet->isCapital()) {
            $population->decrementQuantity($quantity);
        } else {
            $userUnit = $planet->user->units->firstWhere('id', $unit->id);

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
