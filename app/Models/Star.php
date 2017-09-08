<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Positionable as PositionableContract;

/**
 * Star.
 *
 * @property int $id
 * @property string $name
 * @property int $x
 * @property int $y
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Bookmark[] $bookmarks
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
        Relations\HasManyBookmark;

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
     * {@inheritdoc}
     */
    public function toFeature()
    {
        return [
            'type' => 'Feature',
            'properties' => [
                'id' => $this->id,
                'name' => $this->name,
                'type' => 'star',
                'size' => 96,
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    $this->x, $this->y,
                ],
            ],
        ];
    }
}
