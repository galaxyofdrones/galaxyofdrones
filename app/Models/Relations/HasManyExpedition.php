<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Expedition;

trait HasManyExpedition
{
    /**
     * Get the expeditions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expeditions()
    {
        return $this->hasMany(Expedition::class);
    }
}
