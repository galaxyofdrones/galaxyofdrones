<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Planet;

trait BelongsToEnd
{
    /**
     * Get the end.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function end()
    {
        return $this->belongsTo(Planet::class, 'end_id');
    }
}
