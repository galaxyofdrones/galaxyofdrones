<?php

namespace App\Models\Relations;

use App\Models\Movement;

trait HasManyMovement
{
    /**
     * Get the movements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
