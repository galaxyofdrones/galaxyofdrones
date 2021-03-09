<?php

namespace App\Models\Relations;

use App\Models\Stock;

trait HasManyStock
{
    /**
     * Get the stocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
