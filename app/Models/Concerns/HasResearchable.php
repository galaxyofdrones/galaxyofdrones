<?php

namespace Koodilab\Models\Concerns;

use Koodilab\Models\Resource;
use Koodilab\Models\Unit;

trait HasResearchable
{
    /**
     * Has resource?
     *
     * @param resource $resource
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
     * @param Unit $unit
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
