<?php

namespace App\Models;

use App\Contracts\Models\Behaviors\Positionable as PositionableContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Star.
 *
 * @property int                                                                  $id
 * @property string                                                               $name
 * @property int                                                                  $x
 * @property int                                                                  $y
 * @property \Illuminate\Support\Carbon|null                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                      $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Bookmark[]      $bookmarks
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\ExpeditionLog[] $expeditionLogs
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Expedition[]    $expeditions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star inBounds(\App\Support\Bounds $bounds)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Star whereY($value)
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
