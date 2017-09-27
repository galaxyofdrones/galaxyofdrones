<?php

namespace Koodilab\Models\Concerns;

use Koodilab\Models\Building;
use Koodilab\Models\Grid;

trait HasBuilding
{
    /**
     * Find the required buildings.
     *
     * @param int $except
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Building[]
     */
    public function findRequiredBuildings($except = null)
    {
        $ids = $this->grids()->whereNotNull('building_id');

        if ($except) {
            $ids->where('id', '<>', $except);
        }

        $ids = $ids->pluck('building_id')->merge(
            $this->constructions()->pluck('constructions.building_id')
        );

        return Building::defaultOrder()
            ->whereIsRoot()
            ->whereNotIn('id', $ids)
            ->get();
    }

    /**
     * Find the enabled buildings.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Building[]
     */
    public function findEnabledBuildings()
    {
        return $this->grids()
            ->with('building')
            ->whereNotNull('building_id')
            ->where('is_enabled', true)
            ->get([
                'building_id', 'level',
            ])
            ->transform(function (Grid $grid) {
                return $grid->building->applyModifiers([
                    'level' => $grid->level,
                ]);
            });
    }
}
