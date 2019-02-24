<?php

namespace Koodilab\Models\Concerns;

use Koodilab\Models\Planet;

trait HasDistance
{
    /**
     * Get the extra cost by distance.
     *
     * @param Planet $planet
     *
     * @return int
     */
    public function getExtraCostByDistance(Planet $planet)
    {
        return round(
            $this->getDistance($planet) - 1024
        );
    }

    /**
     * Has extra cost by distance?
     *
     * @param Planet $planet
     *
     * @return bool
     */
    public function hasExtraCostByDistance(Planet $planet)
    {
        return $this->getExtraCostByDistance($planet) > 0;
    }

    /**
     * Get the distance.
     *
     * @param Planet $planet
     *
     * @return float
     */
    public function getDistance(Planet $planet)
    {
        return sqrt(pow($planet->x - $this->x, 2) + pow($planet->y - $this->y, 2));
    }
}
