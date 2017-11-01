<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Grid;
use Koodilab\Models\Transformers\UpgradeTransformer;
use Koodilab\Models\Upgrade;

class UpgradeController extends Controller
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
     * Show the upgrade in json format.
     *
     * @param Grid               $grid
     * @param UpgradeTransformer $transformer
     *
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function index(Grid $grid, UpgradeTransformer $transformer)
    {
        $this->authorize('friendly', $grid->planet);

        return $transformer->transform($grid);
    }

    /**
     * Store a newly created upgrade in storage.
     *
     * @param Grid $grid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Grid $grid)
    {
        $this->authorize('friendly', $grid->planet);

        if (!$grid->building_id) {
            return $this->createBadRequestJsonResponse();
        }

        if ($grid->upgrade) {
            return $this->createBadRequestJsonResponse();
        }

        $building = $grid->upgradeBuilding();

        if (!$building) {
            return $this->createBadRequestJsonResponse();
        }

        if (!auth()->user()->hasEnergy($building->construction_cost)) {
            return $this->createBadRequestJsonResponse();
        }

        DB::transaction(function () use ($grid) {
            Upgrade::createFrom($grid);
        });
    }

    /**
     * Remove the upgrade from storage.
     *
     * @param Grid $grid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Grid $grid)
    {
        $this->authorize('friendly', $grid->planet);

        if (!$grid->upgrade) {
            return $this->createBadRequestJsonResponse();
        }

        DB::transaction(function () use ($grid) {
            $grid->upgrade->cancel();
        });
    }
}
