<?php

namespace Koodilab\Models\Concerns;

use Koodilab\Models\Stock;

trait HasCapacity
{
    /**
     * Get the free capacity attribute.
     *
     * @return int
     */
    public function getFreeCapacityAttribute()
    {
        return $this->capacity - $this->used_capacity;
    }

    /**
     * Get the used capacity attribute.
     *
     * @return int
     */
    public function getUsedCapacityAttribute()
    {
        return $this->stocks()
            ->get(['resource_id', 'quantity', 'last_quantity_changed'])
            ->reduce(function ($carry, Stock $stock) {
                return $carry + $stock->setRelation('planet', $this)->quantity;
            }, 0);
    }
}
