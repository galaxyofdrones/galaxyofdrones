<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Grid;
use Koodilab\Models\Transformers\UpgradeTransformer;
use Koodilab\Models\Upgrade;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * @return mixed|\Illuminate\Http\JsonResponse
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
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Grid $grid)
    {
        $this->authorize('friendly', $grid->planet);

        if (! $grid->building_id) {
            throw new BadRequestHttpException();
        }

        if ($grid->upgrade) {
            throw new BadRequestHttpException();
        }

        $building = $grid->upgradeBuilding();

        if (! $building) {
            throw new BadRequestHttpException();
        }

        if (! auth()->user()->hasEnergy($building->construction_cost)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($grid) {
            Upgrade::createFrom(
                $grid
            );
        });
    }

    /**
     * Remove the upgrade from storage.
     *
     * @param Grid $grid
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function destroy(Grid $grid)
    {
        $this->authorize('friendly', $grid->planet);

        if (! $grid->upgrade) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($grid) {
            $grid->upgrade->cancel();
        });
    }
}
