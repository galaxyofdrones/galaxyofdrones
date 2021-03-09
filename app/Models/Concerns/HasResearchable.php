<?php

namespace App\Models\Concerns;

use App\Models\Resource;
use App\Models\Unit;

trait HasResearchable
{
    /**
     * Has resource?
     *
     * @return bool
     */
    public function hasResource(Resource $resource)
    {
        return $this->resources()
            ->where('is_researched', true)
            ->where('resource_id', $resource->id)
            ->exists();
    }

    /**
     * Has unit?
     *
     * @return bool
     */
    public function hasUnit(Unit $unit)
    {
        return $this->units()
            ->where('is_researched', true)
            ->where('unit_id', $unit->id)
            ->exists();
    }
}
