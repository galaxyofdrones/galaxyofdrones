<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Jobs\Upgrade as UpgradeJob;

/**
 * Upgrade.
 *
 * @property int $id
 * @property int $grid_id
 * @property int $level
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $remaining
 * @property Grid $grid
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
     * Create from.
     *
     * @param Grid $grid
     *
     * @return static
     */
    public static function createFrom(Grid $grid)
    {
        $building = $grid->upgradeBuilding();

        auth()->user()->decrementEnergy($building->construction_cost);

        $model = static::create([
            'grid_id' => $grid->id,
            'level' => $building->level,
            'ended_at' => Carbon::now()->addSeconds($building->construction_time),
        ]);

        dispatch(
            (new UpgradeJob($model->id))->delay($model->remaining)
        );

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        $building = $this->grid->upgradeBuilding();

        $this->grid->update([
            'level' => $building->level,
        ]);

        $this->grid->planet->user->incrementExperience(
            $building->construction_experience
        );

        $this->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        $building = $this->grid->upgradeBuilding();

        $this->grid->planet->user->incrementEnergy(round(
            $this->remaining / $building->construction_time * $building->construction_cost
        ));

        $this->delete();
    }
}
