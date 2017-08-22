<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Training;

trait HasManyTraining
{
    /**
     * Get the trainings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
