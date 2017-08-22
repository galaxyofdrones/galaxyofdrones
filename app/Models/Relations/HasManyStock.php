<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Stock;

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
