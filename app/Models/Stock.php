<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Models\Relations\BelongsToPlanet;
use Koodilab\Models\Relations\BelongsToResource;

/**
 * Stock.
 *
 * @property int $id
 * @property int $planet_id
 * @property int $resource_id
 * @property int $quantity
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Planet $planet
 * @property-read resource $resource
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stock extends Model
{
    use BelongsToPlanet, BelongsToResource;

    /**
     * {@inheritdoc}
     */
    protected $perPage = 30;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * Get the quantity attribute.
     *
     * @return int
     */
    public function getQuantityAttribute()
    {
        $quantity = 0;

        if (!empty($this->attributes['quantity'])) {
            $quantity = $this->attributes['quantity'];
        }

        if ($this->resource_id != $this->planet->resource_id) {
            return $quantity;
        }

        $free = $this->planet->capacity - $this->planet->stocks()->sum('quantity');

        $mined = round(
            $this->planet->mining_rate / 3600 * Carbon::now()->diffInSeconds($this->updated_at)
        );

        return $quantity + min($free, $mined);
    }

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
        if ($amount) {
            $free = $this->planet->capacity - $this->planet->used_capacity;

            $this->fill([
                'quantity' => max(0, $this->quantity + min($free, $amount)),
            ]);

            $this->touch();
        }
    }

    /**
     * Decrement the quantity.
     *
     * @param int $amount
     */
    public function decrementQuantity($amount)
    {
        if ($amount) {
            $this->fill([
                'quantity' => max(0, $this->quantity - $amount),
            ]);

            $this->touch();
        }
    }

    /**
     * Synchronize the quantity.
     */
    public function syncQuantity()
    {
        $this->fill([
            'quantity' => $this->quantity,
        ]);

        $this->touch();
    }
}
