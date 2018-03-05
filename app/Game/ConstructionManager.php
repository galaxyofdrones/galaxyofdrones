<?php

namespace Koodilab\Game;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Koodilab\Jobs\Construction as ConstructionJob;
use Koodilab\Models\Building;
use Koodilab\Models\Construction;
use Koodilab\Models\Grid;

class ConstructionManager
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * The bus instance.
     *
     * @var Bus
     */
    protected $bus;

    /**
     * Constructor.
     *
     * @param Auth $auth
     * @param Bus  $bus
     */
    public function __construct(Auth $auth, Bus $bus)
    {
        $this->auth = $auth;
        $this->bus = $bus;
    }

    /**
     * Create.
     *
     * @param Grid     $grid
     * @param Building $building
     *
     * @return Construction
     */
    public function create(Grid $grid, Building $building)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $user->decrementEnergy($building->construction_cost);

        $construction = Construction::create([
            'building_id' => $building->id,
            'grid_id' => $grid->id,
            'level' => $building->level,
            'ended_at' => Carbon::now()->addSeconds($building->construction_time),
        ]);

        $this->bus->dispatch(
            (new ConstructionJob($construction->id))->delay($construction->remaining)
        );

        return $construction;
    }

    /**
     * Finish.
     *
     * @param Construction $construction
     */
    public function finish(Construction $construction)
    {
        $construction->building->applyModifiers([
            'level' => $construction->level,
        ]);

        $construction->grid->update([
            'building_id' => $construction->building->id,
            'level' => $construction->building->level,
        ]);

        $construction->grid->planet->user->incrementExperience(
            $construction->building->construction_experience
        );

        $construction->delete();
    }

    /**
     * Cancel.
     *
     * @param Construction $construction
     */
    public function cancel(Construction $construction)
    {
        $construction->building->applyModifiers([
            'level' => $construction->level,
        ]);

        $construction->grid->planet->user->incrementEnergy(round(
            $construction->remaining / $construction->building->construction_time * $construction->building->construction_cost
        ));

        $construction->delete();
    }
}
