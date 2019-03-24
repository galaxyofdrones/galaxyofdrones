<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Positionable as PositionableContract;

/**
 * Star.
 *
 * @property int                                                                       $id
 * @property string                                                                    $name
 * @property int                                                                       $x
 * @property int                                                                       $y
 * @property \Illuminate\Support\Carbon|null                                           $created_at
 * @property \Illuminate\Support\Carbon|null                                           $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Bookmark[]      $bookmarks
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\ExpeditionLog[] $expeditionLogs
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Expedition[]    $expeditions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star inBounds(\Koodilab\Support\Bounds $bounds)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Star whereY($value)
 * @mixin \Eloquent
 */
class Star extends Model implements PositionableContract
{
    use Behaviors\Positionable,
        Relations\HasManyBookmark,
        Relations\HasManyExpedition,
        Relations\HasManyExpeditionLog;

    /**
     * The find step.
     *
     * @var int
     */
    const FIND_STEP = 1024;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];
}
