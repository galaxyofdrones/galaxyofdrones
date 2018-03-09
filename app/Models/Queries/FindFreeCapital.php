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
        $bounds = new Bounds();

        for ($i = Planet::FIND_STEP; $i < $center; $i += Planet::FIND_STEP) {
            $bounds->setMinXY($center - $i)->setMaxXY($center + $i);

            $capital = Planet::starter()
                ->inBounds($bounds)
                ->first();

            if ($capital) {
                return $capital;
            }
        }

        return null;
    }
}
