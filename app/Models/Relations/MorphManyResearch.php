<?php

namespace App\Models\Relations;

use App\Models\Research;

trait MorphManyResearch
{
    /**
     * Get the researches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function researches()
    {
        return $this->morphMany(Research::class, 'researchable');
    }
}
