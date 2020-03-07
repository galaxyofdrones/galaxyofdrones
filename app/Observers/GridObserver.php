<?php

namespace Koodilab\Observers;

use Koodilab\Game\StateManager;
use Koodilab\Models\Grid;

class GridObserver
{
    /**
     * The state manager instance.
     *
     * @var StateManager
     */
    protected $stateManager;

    /**
     * Constructor.
     */
    public function __construct(StateManager $stateManager)
    {
        $this->stateManager = $stateManager;
    }

    /**
     * Updated.
     */
    public function updated(Grid $grid)
    {
        $this->stateManager->syncPlanet($grid->planet);
    }
}
