<?php

namespace App\Models\Relations;

use App\Models\Training;

trait HasOneTraining
{
    /**
     * Get the training.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function training()
    {
        return $this->hasOne(Training::class);
    }
}
