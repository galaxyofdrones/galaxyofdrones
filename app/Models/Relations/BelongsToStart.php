<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Planet;

trait BelongsToStart
{
    /**
     * Get the start.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function start()
    {
        return $this->belongsTo(Planet::class, 'start_id');
    }
}
