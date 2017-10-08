<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Jobs\Construction as ConstructionJob;

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
 * @property Building $building
 * @property int $remaining
 * @property Grid $grid
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
     * Create from.
     *
     * @param Grid     $grid
     * @param Building $building
     *
     * @return static
     */
    public static function createFrom(Grid $grid, Building $building)
    {
        auth()->user()->decrementEnergy($building->construction_cost);

        $model = static::create([
            'building_id' => $building->id,
            'grid_id' => $grid->id,
            'level' => $building->level,
            'ended_at' => Carbon::now()->addSeconds($building->construction_time),
        ]);

        dispatch(
            (new ConstructionJob($model->id))->delay($model->remaining)
        );

        event(
            new PlanetUpdated($grid->planet_id)
        );

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        $this->building->applyModifiers([
            'level' => $this->level,
        ]);

        $this->grid->update([
            'building_id' => $this->building->id,
            'level' => $this->building->level,
        ]);

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
        $this->building->applyModifiers([
            'level' => $this->level,
        ]);

        $this->grid->planet->user->incrementEnergy(round(
            $this->remaining / $this->building->construction_time * $this->building->construction_cost
        ));

        $this->delete();

        event(
            new PlanetUpdated($this->grid->planet_id)
        );
    }
}
