<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;

/**
 * Construction.
 *
 * @property int $id
 * @property int $building_id
 * @property int $grid_id
 * @property int $level
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Building $building
 * @property-read int $remaining
 * @property-read Grid $grid
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Construction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Construction extends Model implements TimeableContract
{
    use Behaviors\Timeable,
        Relations\BelongsToBuilding,
        Relations\BelongsToGrid;

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
    protected $dates = [
        'ended_at',
    ];

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        $this->building->applyModifiers([
            'level' => $this->level,
        ]);

        $this->grid->fill([
            'level' => $this->building->level,
        ]);

        $this->grid->building()->associate($this->building->id);
        $this->grid->save();

        $this->grid->planet->user->incrementExperience(
            $this->building->construction_experience
        );

        $this->delete();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
    }
}
