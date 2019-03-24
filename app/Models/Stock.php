<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Stock.
 *
 * @property int                             $id
 * @property int                             $planet_id
 * @property int                             $resource_id
 * @property int                             $quantity
 * @property \Illuminate\Support\Carbon|null $last_quantity_changed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Koodilab\Models\Planet         $planet
 * @property \Koodilab\Models\Resource       $resource
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock whereLastQuantityChanged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Stock whereUpdatedAt($value)
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
