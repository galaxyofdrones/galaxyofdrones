<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Research;

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
