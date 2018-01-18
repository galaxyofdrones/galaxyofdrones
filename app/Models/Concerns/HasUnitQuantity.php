<?php

namespace Koodilab\Models\Concerns;

trait HasUnitQuantity
{
    use HasQuantity;

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

        $free = $this->planet->free_supply;
        $supply = $amount * $this->unit->supply;

        if ($free < $supply) {
            $amount = floor($free / $this->unit->supply);
        }

        $this->fill([
            'quantity' => max(0, $this->quantity + $amount),
        ])->touch();
    }
}
