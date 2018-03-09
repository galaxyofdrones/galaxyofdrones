<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Positionable as PositionableContract;

/**
 * Star.
 *
 * @property int                                                      $id
 * @property string                                                   $name
 * @property int                                                      $x
 * @property int                                                      $y
 * @property \Carbon\Carbon|null                                      $created_at
 * @property \Carbon\Carbon|null                                      $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|Bookmark[]      $bookmarks
 * @property \Illuminate\Database\Eloquent\Collection|ExpeditionLog[] $expeditionLogs
 * @property \Illuminate\Database\Eloquent\Collection|Expedition[]    $expeditions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Star inBounds(\Koodilab\Support\Bounds $bounds)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereY($value)
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
