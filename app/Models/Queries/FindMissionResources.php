<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Resource;

trait FindMissionResources
{
    /**
     * Find the mission resources.
     *
     * @param array $columns
     *
     * @return \Illuminate\Support\Collection|\Koodilab\Models\Resource[]
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
