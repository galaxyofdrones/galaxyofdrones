<?php

namespace Koodilab\Models\Concerns;

use Carbon\Carbon;

trait HasResourceQuantity
{
    /**
     * Has quantity?
     *
     * @param int $quantity
     *
     * @return bool
     */
    public function hasQuantity($quantity)
    {
        return $this->quantity >= $quantity;
    }

    /**
     * Get the quantity attribute.
     *
     * @return int
     */
    public function getQuantityAttribute()
    {
        $quantity = ! empty($this->attributes['quantity'])
            ? $this->attributes['quantity']
            : 0;

        if ($this->resource_id != $this->planet->resource_id) {
            return $quantity;
        }

        $free = $this->planet->capacity - $this->planet->stocks()->sum('quantity');

        $mined = round(
            $this->planet->mining_rate / 3600 * Carbon::now()->diffInSeconds($this->last_quantity_changed)
        );

        return $quantity + min($free, $mined);
    }

    /**
     * Increment the quantity.
     *
     * @param int $amount
     */
    public function incrementQuantity($amount)
    {
        if (empty($amount)) {
            return;
        }

        $this->fill([
            'quantity' => max(0, $this->quantity + min($this->planet->free_capacity, $amount)),
            'last_quantity_changed' => Carbon::now(),
        ])->save();
    }

    /**
     * Decrement the quantity.
     *
     * @param int $amount
     */
    public function decrementQuantity($amount)
    {
        if (empty($amount)) {
            return;
        }

        $this->fill([
            'quantity' => max(0, $this->quantity - $amount),
            'last_quantity_changed' => Carbon::now(),
        ])->save();
    }
}
