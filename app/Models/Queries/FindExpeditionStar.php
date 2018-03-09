<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Star;
use Koodilab\Support\Bounds;

trait FindExpeditionStar
{
    /**
     * Find an expedition star.
     *
     * @return Star
     */
    public function findExpeditionStar()
    {
        $except = $this->expeditions()->pluck('star_id');
        $bounds = new Bounds();

        for ($i = Star::FIND_STEP; $i < $this->capital->x; $i += Star::FIND_STEP) {
            $bounds->setMinXY($this->capital->x - $i)->setMaxXY($this->capital->x + $i);

            $star = Star::whereNotIn('id', $except)
                ->inBounds($bounds)
                ->first();

            if ($star) {
                return $star;
            }
        }

        return null;
    }
}
