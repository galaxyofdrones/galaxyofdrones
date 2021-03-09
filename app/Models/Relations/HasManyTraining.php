<?php

namespace App\Models\Relations;

use App\Models\Training;

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
