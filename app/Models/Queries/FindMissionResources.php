<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Resource;

trait FindMissionResources
{
    /**
     * Find mission resources.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|resource[]
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
