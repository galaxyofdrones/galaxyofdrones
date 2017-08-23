<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Positionable as PositionableContract;
use Koodilab\Models\Behaviors\Positionable;
use Koodilab\Models\Relations\BelongsToResource;
use Koodilab\Models\Relations\BelongsToUser;

/**
 * Planet.
 *
 * @property int $id
 * @property int $resource_id
 * @property int|null $user_id
 * @property string $name
 * @property string $custom_name
 * @property int $x
 * @property int $y
 * @property int $size
 * @property int|null $capacity
 * @property int|null $supply
 * @property int|null $mining_rate
 * @property int|null $production_rate
 * @property float|null $defense_bonus
 * @property float|null $construction_time_bonus
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read string $display_name
 * @property-read int $resource_quantity
 * @property-read resource $resource
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Planet inBounds(\Koodilab\Support\Bounds $bounds)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereConstructionTimeBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereCustomName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereDefenseBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereMiningRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereProductionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereY($value)
 * @mixin \Eloquent
 */
class Planet extends Model implements PositionableContract
{
    use Positionable, BelongsToResource, BelongsToUser;

    /**
     * The small size.
     *
     * @var int
     */
    const SIZE_SMALL = 0;

    /**
     * The medium size.
     *
     * @var int
     */
    const SIZE_MEDIUM = 1;

    /**
     * The large size.
     *
     * @var int
     */
    const SIZE_LARGE = 2;

    /**
     * The resource count.
     *
     * @var int
     */
    const RESOURCE_COUNT = 3;

    /**
     * The settler count.
     *
     * @var int
     */
    const SETTLER_COUNT = 1;

    /**
     * Astronomical unit per pixel.
     *
     * @var int
     */
    const AU_PER_PIXEL = 256;

    /**
     * Time offset.
     *
     * @var int
     */
    const TIME_OFFSET = 500;

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
     * Set the custom name attribute.
     *
     * @param string $value
     */
    public function setCustomNameAttribute($value)
    {
        $this->attributes['custom_name'] = $this->name != $value
            ? $value
            : null;
    }

    /**
     * Get the resource quantity attribute.
     *
     * @return int
     */
    public function getResourceQuantityAttribute()
    {
        return static::RESOURCE_COUNT + $this->size;
    }

    /**
     * Get the display name attribute.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->custom_name ?: $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function toFeature()
    {
        $status = 'free';

        if ($this->user_id) {
            /** @var User $user */
            $user = auth()->user();

            if ($user && $user->current_id == $this->id) {
                $status = 'current';
            } elseif ($user && $user->id == $this->user_id) {
                $status = 'friendly';
            } elseif ($this->user->capital_id == $this->id) {
                $status = 'capital';
            } else {
                $status = 'hostile';
            }
        }

        return [
            'type' => 'Feature',
            'properties' => [
                'id' => $this->id,
                'name' => $this->display_name,
                'type' => 'planet',
                'size' => 32 + ($this->size * 16),
                'status' => $status,
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
