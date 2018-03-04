<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Stock.
 *
 * @property int                 $id
 * @property int                 $planet_id
 * @property int                 $resource_id
 * @property int                 $quantity
 * @property \Carbon\Carbon|null $last_quantity_changed
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property Planet              $planet
 * @property resource            $resource
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereLastQuantityChanged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stock extends Model
{
    use Concerns\HasResourceQuantity,
        Relations\BelongsToPlanet,
        Relations\BelongsToResource;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'last_quantity_changed',
    ];
}
