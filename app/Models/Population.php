<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Population.
 *
 * @property int                             $id
 * @property int                             $planet_id
 * @property int                             $unit_id
 * @property int                             $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Koodilab\Models\Planet         $planet
 * @property \Koodilab\Models\Unit           $unit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Population newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Population newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Population query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Population whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Population whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Population wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Population whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Population whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Population whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Population extends Model
{
    use Concerns\HasUnitQuantity,
        Relations\BelongsToPlanet,
        Relations\BelongsToUnit;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];
}
