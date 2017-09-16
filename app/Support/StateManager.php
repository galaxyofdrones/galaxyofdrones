<?php

namespace Koodilab\Support;

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
        $planet->createOrUpdateStock();

        $attributes = (new Collection($planet->attributesToArray()))->only([
            'capacity', 'supply', 'mining_rate', 'production_rate', 'defense_bonus', 'construction_time_bonus',
        ])->transform(function () {
            return 0;
        });

        $planet->findEnabledBuildings()->each(function (Building $building) use ($attributes) {
            $attributes->transform(function ($value, $key) use ($building) {
                return $value + $building->{$key};
            });
        });

        $attributes = $attributes->filter();

        if ($attributes->has('mining_rate', 'production_rate')) {
            $miningRate = $attributes->get('mining_rate');

            if ($miningRate > $attributes->get('production_rate')) {
                $attributes->put('mining_rate', $miningRate - $attributes->get('production_rate'));
            } else {
                $attributes->put('production_rate', $miningRate);
                $attributes->put('mining_rate', null);
            }
        }

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
        ]);
    }
}
