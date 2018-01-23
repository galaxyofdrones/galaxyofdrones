<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Resource;

trait FindAvailableResource
{
    /**
     * Find available resource.
     *
     * @param array $columns
     *
     * @return \Koodilab\Models\Resource
     */
    public function findAvailableResource($columns = ['*'])
    {
        $except = $this->resources()
            ->where('is_researched', true)
            ->pluck('resource_id');

        return Resource::whereNotIn('id', $except)
            ->orderBy('sort_order')
            ->first($columns);
    }
}
