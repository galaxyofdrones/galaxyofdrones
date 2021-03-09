<?php

namespace App\Models\Queries;

use App\Models\Building;

trait FindAllByBuilding
{
    /**
     * Find all by building.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function findAllByBuilding(Building $building, $columns = ['*'])
    {
        return static::where('building_id', $building->id)->get($columns);
    }
}
