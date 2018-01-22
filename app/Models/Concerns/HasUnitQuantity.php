<?php

namespace Koodilab\Models\Concerns;

trait HasUnitQuantity
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
     * Increment the quantity.
     *
     * @param int $amount
     */
    public function incrementQuantity($amount)
    {
        if (empty($amount)) {
            return;
        }

        $free = $this->planet->supply - $this->planet->used_supply;
        $supply = $amount * $this->unit->supply;

        if ($free < $supply) {
            $amount = floor($free / $this->unit->supply);
        }

        $this->fill([
            'quantity' => max(0, $this->quantity + $amount),
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
        ])->save();
    }
}
