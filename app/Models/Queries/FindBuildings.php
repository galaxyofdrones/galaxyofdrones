<?php

namespace App\Models\Queries;

use App\Models\Grid;

trait FindBuildings
{
    /**
     * Find the buildings.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Building[]
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
                return $grid->building->replicate()
                    ->applyModifiers([
                        'level' => $grid->level,
                    ]);
            });
    }
}
