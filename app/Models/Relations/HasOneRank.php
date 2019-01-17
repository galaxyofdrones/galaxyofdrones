<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Rank;

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
