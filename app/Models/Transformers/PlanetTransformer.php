<?php

namespace Koodilab\Models\Transformers;

use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;
use Koodilab\Models\User;

class PlanetTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param Planet $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'resource_id' => $item->resource_id,
            'user_id' => $item->user_id,
            'name' => $item->name,
            'display_name' => $item->display_name,
            'x' => $item->x,
            'y' => $item->y,
            'capacity' => $item->capacity,
            'supply' => $item->supply,
            'solarion' => $item->user->solarion,
            'mining_rate' => (int) $item->mining_rate,
            'production_rate' => (int) $item->production_rate,
            'incoming_movement' => $item->incomingMovementCount(),
            'incoming_attack_movement' => $item->incomingAttackMovementCount(),
            'outgoing_movement' => $item->outgoingMovementCount(),
            'outgoing_attack_movement' => $item->outgoingAttackMovementCount(),
            'construction' => $item->constructions()->count(),
            'upgrade' => $item->upgrades()->count(),
            'training' => $item->trainings()->count(),
            'used_capacity' => $item->used_capacity,
            'used_supply' => $item->used_supply,
            'used_training_supply' => $item->used_training_supply,
            'is_capital' => $item->isCapital(),
            'planets' => $this->planets($item->user),
            'resources' => $this->resources($item),
            'units' => $this->units($item),
            'grids' => $this->grids($item),
        ];
    }

    /**
     * Get the planets.
     *
     * @param User $user
     *
     * @return array
     */
    protected function planets(User $user)
    {
        return $user->findPlanetsOrderByName()
            ->transform(function (Planet $planet) {
                return [
                    'id' => $planet->id,
                    'name' => $planet->display_name,
                ];
            });
    }

    /**
     * Get the resources.
     *
     * @param Planet $planet
     *
     * @return array
     */
    protected function resources(Planet $planet)
    {
        $stocks = $planet->stocks->keyBy('resource_id');

        return Resource::newModelInstance()
            ->findAllOrderBySortOrder()
            ->transform(function (Resource $resource) use ($planet, $stocks) {
                $userResource = $planet->isCapital()
                    ? $planet->user->resources->firstWhere('id', $resource->id)
                    : null;

                return [
                    'id' => $resource->id,
                    'name' => $resource->translation('name'),
                    'description' => $resource->translation('description'),
                    'quantity' => $stocks->has($resource->id)
                        ? $stocks->get($resource->id)->quantity
                        : 0,
                    'storage' => $userResource
                        ? $userResource->pivot->quantity
                        : 0,
                ];
            });
    }

    /**
     * Get the units.
     *
     * @param Planet $planet
     *
     * @return array
     */
    protected function units(Planet $planet)
    {
        $populations = $planet->populations->keyBy('unit_id');

        return Unit::newModelInstance()
            ->findAllOrderBySortOrder()
            ->transform(function (Unit $unit) use ($planet, $populations) {
                $userUnit = $planet->isCapital()
                    ? $planet->user->units->firstWhere('id', $unit->id)
                    : null;

                return [
                    'id' => $unit->id,
                    'name' => $unit->translation('name'),
                    'type' => $unit->type,
                    'speed' => $unit->speed,
                    'description' => $unit->translation('description'),
                    'capacity' => $unit->capacity,
                    'quantity' => $populations->has($unit->id)
                        ? $populations->get($unit->id)->quantity
                        : 0,
                    'storage' => $userUnit
                        ? $userUnit->pivot->quantity
                        : 0,
                ];
            });
    }

    /**
     * Get the grids.
     *
     * @param Planet $planet
     *
     * @return array
     */
    protected function grids(Planet $planet)
    {
        return $planet->findGrids()
            ->map(function (Grid $grid) {
                return [
                    'id' => $grid->id,
                    'building_id' => $grid->building_id,
                    'x' => $grid->x,
                    'y' => $grid->y,
                    'level' => $grid->level,
                    'type' => $grid->type,
                    'construction' => $grid->construction
                        ? [
                            'building_id' => $grid->construction->building_id,
                            'level' => $grid->construction->level,
                            'remaining' => $grid->construction->remaining,
                        ]
                        : null,
                    'upgrade' => $grid->upgrade
                        ? [
                            'level' => $grid->upgrade->level,
                            'remaining' => $grid->upgrade->remaining,
                        ]
                        : null,
                    'training' => $grid->training
                        ? [
                            'remaining' => $grid->training->remaining,
                        ]
                        : null,
                ];
            });
    }
}
