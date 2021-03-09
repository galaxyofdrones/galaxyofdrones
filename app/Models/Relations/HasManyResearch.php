<?php

namespace App\Models\Relations;

use App\Models\Research;

trait HasManyResearch
{
    /**
     * Get the researches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function researches()
    {
        return $this->hasMany(Research::class);
    }
}
