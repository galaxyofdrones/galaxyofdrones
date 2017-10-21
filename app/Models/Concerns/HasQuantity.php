<?php

namespace Koodilab\Models\Concerns;

trait HasQuantity
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
        ])->save();
    }
}
