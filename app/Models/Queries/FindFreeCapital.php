<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Planet;
use Koodilab\Starmap\Generator;
use Koodilab\Support\Bounds;

trait FindFreeCapital
{
    /**
     * Find a free capital.
     *
     * @return Planet
     */
    public static function findFreeCapital()
    {
        $center = Generator::SIZE / 2;
        $query = Planet::starter();
        $bounds = new Bounds();

        for ($i = Planet::CAPITAL_STEP; $i < $center; $i += Planet::CAPITAL_STEP) {
            $capital = $query->inBounds(
                $bounds->setMinXY($center - $i)->setMaxXY($center + $i)
            )->first();

            if ($capital) {
                return $capital;
            }
        }

        return null;
    }
}
