<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Grid;

trait FindBuildings
{
    /**
     * Find the buildings.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Building[]
     */
    public function findBuildings()
    {
        return $this->grids()
            ->with('building')
            ->whereNotNull('building_id')
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
