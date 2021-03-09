<?php

namespace App\Models\Relations;

use App\Models\Resource;

trait BelongsToResource
{
    /**
     * Get the resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
