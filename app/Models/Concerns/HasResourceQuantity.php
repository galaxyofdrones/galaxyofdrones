<?php

namespace Koodilab\Models\Concerns;

use Carbon\Carbon;

trait HasResourceQuantity
{
    use HasQuantity;

    /**
     * The "booting" method of the trait.
     */
    public static function bootHasResourceQuantity()
    {
        static::saving(function ($model) {
            if ($model->isDirty('quantity')) {
                $model->last_quantity_changed = Carbon::now();
            }
        });
    }

    /**
     * Get the quantity attribute.
     *
     * @return int
     */
    public function getQuantityAttribute()
    {
        $quantity = !empty($this->attributes['quantity'])
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

        $free = $this->planet->capacity - $this->planet->used_capacity;

        $this->fill([
            'quantity' => max(0, $this->quantity + min($free, $amount)),
        ])->save();
    }
}
