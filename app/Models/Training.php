<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Jobs\Train as TrainJob;

/**
 * Training.
 *
 * @property int $id
 * @property int $grid_id
 * @property int $unit_id
 * @property int $quantity
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $remaining
 * @property Grid $grid
 * @property Unit $unit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereGridId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Training extends Model implements TimeableContract
{
    use Behaviors\Timeable,
        Relations\BelongsToGrid,
        Relations\BelongsToUnit;

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
     * @param Unit $unit
     * @param int  $quantity
     *
     * @return static
     */
    public static function createFrom(Grid $grid, Unit $unit, $quantity)
    {
        auth()->user()->decrementEnergy($quantity * $unit->train_cost);

        $model = static::create([
            'grid_id' => $grid->id,
            'unit_id' => $unit->id,
            'quantity' => $quantity,
            'ended_at' => Carbon::now()->addSeconds($quantity * $unit->train_time),
        ]);

        dispatch(
            (new TrainJob($model->id))->delay($model->remaining)
        );

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        $this->grid->planet->createOrUpdatePopulation(
            $this->unit, $this->quantity
        );

        $this->delete();

        event(
            new PlanetUpdated($this->grid->planet_id)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        $totalTime = $this->grid->training->quantity * $this->grid->training->unit->train_time;
        $totalCost = $this->grid->training->quantity * $this->grid->training->unit->train_cost;

        $this->grid->planet->user->incrementEnergy(round(
            $this->remaining / $totalTime * $totalCost
        ));

        $this->delete();
    }
}
