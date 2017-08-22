<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Star;

trait BelongsToStar
{
    /**
     * Get the star.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function star()
    {
        return $this->belongsTo(Star::class);
    }
}
