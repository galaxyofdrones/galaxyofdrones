<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Events\PlanetUpdated;

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
 * @property-read int $remaining
 * @property-read Grid $grid
 * @property-read Unit $unit
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
     * {@inheritdoc}
     */
    public function finish()
    {
        /** @var Population $population */
        $population = $this->grid->planet->populations()->firstOrNew([
            'unit_id' => $this->unit_id,
        ]);

        $population->setRelation('planet', $this->grid->planet)
            ->setRelation('unit', $this->unit)
            ->incrementQuantity($this->quantity);

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
