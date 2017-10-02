<?php

namespace Koodilab\Models\Concerns;

use Koodilab\Models\Population;
use Koodilab\Models\Stock;
use Koodilab\Models\Training;

trait HasCapacityAndSupply
{
    /**
     * Get the used capacity attribute.
     *
     * @return int
     */
    public function getUsedCapacityAttribute()
    {
        return $this->stocks()
            ->get(['resource_id', 'quantity', 'last_quantity_changed'])
            ->reduce(function ($carry, Stock $stock) {
                return $carry + $stock->setRelation('planet', $this)->quantity;
            }, 0);
    }

    /**
     * Get the used supply attribute.
     *
     * @return int
     */
    public function getUsedSupplyAttribute()
    {
        return $this->populations()
            ->with([
                'unit' => function ($query) {
                    $query->select('id', 'supply');
                },
            ])
            ->get(['unit_id', 'quantity'])
            ->reduce(function ($carry, Population $population) {
                return $carry + $population->quantity * $population->unit->supply;
            }, 0);
    }

    /**
     * Get the used training supply attribute.
     *
     * @return int
     */
    public function getUsedTrainingSupplyAttribute()
    {
        return $this->trainings()
            ->with([
                'unit' => function ($query) {
                    $query->select('id', 'supply');
                },
            ])
            ->get(['unit_id', 'quantity'])
            ->reduce(function ($carry, Training $training) {
                return $carry + $training->quantity * $training->unit->supply;
            }, 0);
    }
}
