<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Resource;

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
