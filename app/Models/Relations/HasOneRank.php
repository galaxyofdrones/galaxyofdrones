<?php

namespace App\Models\Relations;

use App\Models\Rank;

trait HasOneRank
{
    /**
     * Get the rank.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rank()
    {
        return $this->hasOne(Rank::class);
    }
}
