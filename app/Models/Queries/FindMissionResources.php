<?php

namespace App\Models\Queries;

use App\Models\Resource;

trait FindMissionResources
{
    /**
     * Find the mission resources.
     *
     * @param array $columns
     *
     * @return \Illuminate\Support\Collection|\App\Models\Resource[]
     */
    public function findMissionResources($columns = ['*'])
    {
        $resourceIds = $this->planets()
            ->distinct()
            ->pluck('resource_id');

        return Resource::whereIn('id', $resourceIds)
            ->orderBy('sort_order')
            ->get($columns);
    }
}
