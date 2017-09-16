<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
use Koodilab\Models\Transformers\Transformer;
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
            'mining_rate' => (int) $item->mining_rate,
            'production_rate' => (int) $item->production_rate,
            'incoming_movement' => $item->incomingMovements()->count(),
            'incoming_attack_movement' => $item->incomingAttackMovementCount(),
            'outgoing_movement' => $item->outgoingMovements()->count(),
            'outgoing_attack_movement' => $item->outgoingAttackMovementCount(),
            'construction' => $item->constructions()->count(),
            'upgrade' => $item->upgrades()->count(),
            'training' => $item->trainings()->count(),
            'used_capacity' => $item->used_capacity,
            'used_supply' => $item->used_supply,
            'used_training_supply' => $item->used_training_supply,
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
        return $user->findAllPlanetsOrderByName()
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

        return Resource::scopedModel()
            ->findAllOrderBySortOrder()
            ->transform(function (Resource $resource) use ($stocks) {
                return [
                    'id' => $resource->id,
                    'name' => $resource->translation('name'),
                    'description' => $resource->translation('description'),
                    'quantity' => $stocks->has($resource->id)
                        ? $stocks->get($resource->id)->quantity
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

        return Unit::scopedModel()
            ->findAllOrderBySortOrder()
            ->transform(function (Unit $unit) use ($populations) {
                return [
                    'id' => $unit->id,
                    'name' => $unit->translation('name'),
                    'description' => $unit->translation('description'),
                    'quantity' => $populations->has($unit->id)
                        ? $populations->get($unit->id)->quantity
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
        return $planet->findGridsWithConstructionAndUpgrade()
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
                ];
            });
    }
}
