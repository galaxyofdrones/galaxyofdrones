<?php

namespace Koodilab\Game;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Koodilab\Models\Building;
use Koodilab\Models\Planet;
use Koodilab\Models\User;

class StateManager
{
    /**
     * Synchronize the planet.
     *
     * @param Planet $planet
     */
    public function syncPlanet(Planet $planet)
    {
        /** @var \Koodilab\Models\Stock $stock */
        $stock = $planet->stocks()->firstOrNew([
            'resource_id' => $planet->resource_id,
        ]);

        $stock->setRelation('planet', $planet)->fill([
            'quantity' => max(0, $stock->quantity),
            'last_quantity_changed' => Carbon::now(),
        ])->save();

        $attributes = (new Collection($planet->attributesToArray()))->only([
            'capacity', 'supply', 'mining_rate', 'production_rate', 'defense_bonus', 'construction_time_bonus',
        ])->transform(function () {
            return null;
        });

        $planet->findBuildings()->each(function (Building $building) use ($attributes) {
            $attributes->transform(function ($value, $key) use ($building) {
                return $value + $building->{$key};
            });
        });

        $planet->update(
            $attributes->toArray()
        );
    }

    /**
     * Synchronize the user.
     *
     * @param User $user
     */
    public function syncUser(User $user)
    {
        $user->update([
            'energy' => $user->energy,
            'production_rate' => $user->planets->sum('production_rate'),
            'last_energy_changed' => Carbon::now(),
        ]);
    }
}
