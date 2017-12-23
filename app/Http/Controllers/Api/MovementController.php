<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\Unit;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MovementController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('player');
    }

    /**
     * Store a newly created trading in storage.
     *
     * @param Planet $planet
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeScout(Planet $planet)
    {
        $this->authorize('hostile', $planet);

        $quantity = $this->quantity();

        /** @var \Koodilab\Models\Population $population */
        $population = auth()->user()->current->findPopulationByUnit(
            Unit::findByType(Unit::TYPE_SCOUT)
        );

        if (!$population || !$population->hasQuantity($quantity)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($planet, $population, $quantity) {
            Movement::createScoutFrom($planet, $population, $quantity);
        });
    }

    public function storeAttack(Planet $planet, Request $request)
    {
        $this->authorize('hostile', $planet);

        $quantities = $this->quantities();
    }

    public function storeOccupy(Planet $planet)
    {
        $this->authorize('hostile', $planet);
    }

    public function storeSupport(Planet $planet)
    {
        $this->authorize('friendly', $planet);

        $quantities = $this->quantities();
    }

    public function storeTransport(Planet $planet)
    {
        $this->authorize('friendly', $planet);

        $quantities = $this->quantities();
    }
}
