<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Building;

trait BelongsToBuilding
{
    /**
     * Get the building.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
