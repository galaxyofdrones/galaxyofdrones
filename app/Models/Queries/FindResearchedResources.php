<?php

namespace Koodilab\Models\Queries;

trait FindResearchedResources
{
    /**
     * Find researched resources order by sort order.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Resource[]
     */
    public function findResearchedResources($columns = ['*'])
    {
        return $this->resources()
            ->where('is_researched', true)
            ->orderBy('sort_order')
            ->get($columns);
    }
}
