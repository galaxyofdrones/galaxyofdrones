<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Translatable as TranslatableContract;

/**
 * Setting.
 *
 * @property int                             $id
 * @property string                          $key
 * @property array                           $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Setting whereValue($value)
 * @mixin \Eloquent
 */
class Setting extends Model implements TranslatableContract
{
    use Behaviors\Translatable;

    /**
     * {@inheritdoc}
     */
    protected $attributes = [
        'value' => '{}',
    ];

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'value' => 'json',
    ];
}
