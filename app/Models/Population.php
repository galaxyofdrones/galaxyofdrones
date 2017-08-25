<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Models\Relations\BelongsToPlanet;
use Koodilab\Models\Relations\BelongsToUnit;

/**
 * Population.
 *
 * @property int $id
 * @property int $planet_id
 * @property int $unit_id
 * @property int $quantity
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Planet $planet
 * @property-read Unit $unit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Population whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Population extends Model
{
    use BelongsToPlanet, BelongsToUnit;

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
            $free = $this->planet->supply - $this->planet->used_supply;
            $supply = $amount * $this->unit->supply;

            if ($free < $supply) {
                $amount = floor($free / $this->unit->supply);
            }

            $this->fill([
                'quantity' => max(0, $this->quantity + $amount),
            ]);

            $this->save();
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

            $this->save();
        }
    }
}
