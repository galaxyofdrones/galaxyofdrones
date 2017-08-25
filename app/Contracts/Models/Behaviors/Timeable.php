<?php

namespace Koodilab\Contracts\Models\Behaviors;

interface Timeable
{
    /**
     * Get the remaining attribute.
     *
     * @return int
     */
    public function getRemainingAttribute();

    /**
     * Finish.
     *
     * @return bool
     */
    public function finish();

    /**
     * Cancel.
     */
    public function cancel();
}
