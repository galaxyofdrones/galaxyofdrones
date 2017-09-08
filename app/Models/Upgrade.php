<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Events\PlanetUpdated;

/**
 * Upgrade.
 *
 * @property int $id
 * @property int $grid_id
 * @property int $level
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read int $remaining
 * @property-read Grid $grid
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upgrade whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Upgrade extends Model implements TimeableContract
{
    use Behaviors\Timeable,
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
        $this->grid->building->applyModifiers([
            'level' => $this->level,
        ]);

        $this->grid->update([
            'level' => $this->grid->building->level,
        ]);

        $this->grid->planet->user->experience += $this->grid->building->construction_experience;
        $this->grid->planet->user->save();

        $this->delete();

        event(new PlanetUpdated($this->grid->planet_id));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
    }
}
