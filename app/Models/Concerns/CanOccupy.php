<?php

namespace App\Models\Concerns;

use App\Models\Building;
use App\Models\Grid;
use App\Models\Planet;

trait CanOccupy
{
    /**
     * Can occupy?
     *
     * @return bool
     */
    public function canOccupy(Planet $planet)
    {
        if ($this->id == $planet->user_id) {
            return false;
        }

        if ($this->where('capital_id', $planet->id)->exists()) {
            return false;
        }

        if ($this->isStarted() && ! $this->hasResource($planet->resource)) {
            return false;
        }

        return true;
    }

    /**
     * Occupy.
     *
     * @return bool
     */
    public function occupy(Planet $planet)
    {
        if (! $this->canOccupy($planet)) {
            return false;
        }

        if ($planet->user_id && $planet->id == $planet->user->current_id) {
            $planet->user->update([
                'current_id' => $planet->user->capital_id,
            ]);
        }

        $planet->update([
            'user_id' => $this->id,
        ]);

        $buildingId = Building::where('type', Building::TYPE_CENTRAL)->value('id');

        $planet->grids()
            ->where('type', Grid::TYPE_CENTRAL)
            ->first()
            ->update([
                'building_id' => $buildingId,
                'level' => 1,
            ]);

        return true;
    }
}
