<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Resource;

trait BelongsToManyResource
{
    /**
     * Get the resources.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources()
    {
        return $this->belongsToMany(Resource::class);
    }
}
