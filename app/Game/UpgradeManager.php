<?php

namespace Koodilab\Game;

use Carbon\Carbon;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Koodilab\Jobs\Upgrade as UpgradeJob;
use Koodilab\Models\Grid;
use Koodilab\Models\Upgrade;

class UpgradeManager
{
    /**
     * The bus instance.
     *
     * @var Bus
     */
    protected $bus;

    /**
     * Constructor.
     *
     * @param Bus $bus
     */
    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
    }

    /**
     * Create.
     *
     * @param Grid $grid
     *
     * @return Upgrade
     */
    public function create(Grid $grid)
    {
        $building = $grid->upgradeBuilding();

        $grid->planet->user->decrementEnergy($building->construction_cost);

        $upgrade = Upgrade::create([
            'grid_id' => $grid->id,
            'level' => $building->level,
            'ended_at' => Carbon::now()->addSeconds($building->construction_time),
        ]);

        $this->bus->dispatch(
            (new UpgradeJob($upgrade->id))->delay($upgrade->remaining)
        );

        return $upgrade;
    }

    /**
     * Finish.
     *
     * @param Upgrade $upgrade
     */
    public function finish(Upgrade $upgrade)
    {
        $building = $upgrade->grid->upgradeBuilding();

        $upgrade->grid->update([
            'level' => $building->level,
        ]);

        $upgrade->grid->planet->user->incrementExperience(
            $building->construction_experience
        );

        $upgrade->delete();
    }

    /**
     * Cancel.
     *
     * @param Upgrade $upgrade
     */
    public function cancel(Upgrade $upgrade)
    {
        $building = $upgrade->grid->upgradeBuilding();

        $energy = round(
            $upgrade->remaining / $building->construction_time * $building->construction_cost
        );

        $upgrade->grid->planet->user->incrementEnergy($energy);

        $upgrade->delete();
    }
}
