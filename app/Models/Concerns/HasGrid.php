<?php

namespace App\Models\Concerns;

use App\Models\Grid;

trait HasGrid
{
    /**
     * Get the upgrade cost of all grids.
     *
     * @return int
     */
    public function upgradeCost()
    {
        return $this->findNotEmptyGrids()->reduce(function ($carry, Grid $item) {
            if ($item->upgrade) {
                return $carry;
            }

            $upgrade = $item->upgradeBuilding();

            if (! $upgrade) {
                return $carry;
            }

            return $carry + $upgrade->construction_cost;
        }, 0);
    }
}
