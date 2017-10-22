<?php

namespace Koodilab\Models\Concerns;

use Koodilab\Models\Population;
use Koodilab\Models\Training;

trait HasSupply
{
    /**
     * Get the free supply attribute.
     *
     * @return int
     */
    public function getFreeSupplyAttribute()
    {
        return $this->supply - $this->used_supply - $this->used_training_supply;
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
