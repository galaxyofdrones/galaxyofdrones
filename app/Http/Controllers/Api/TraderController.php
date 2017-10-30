<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Mission;
use Koodilab\Models\Transformers\Site\TraderTransformer;

class TraderController extends Controller
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
     * Show the trader in json format.
     *
     * @param TraderTransformer $transformer
     * @param Grid              $grid
     *
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function index(Grid $grid, TraderTransformer $transformer)
    {
        $this->authorizeTrader($grid);

        return $transformer->transform($grid);
    }

    /**
     * Store a newly created trading in storage.
     *
     * @param Grid    $grid
     * @param Mission $mission
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Grid $grid, Mission $mission)
    {
        $this->authorizeTrader($grid);

        if ($grid->planet_id != $mission->planet_id) {
            return $this->createBadRequestJsonResponse();
        }

        if (!$mission->isCompletable()) {
            return $this->createBadRequestJsonResponse();
        }

        DB::transaction(function () use ($mission) {
            $mission->finish();
        });
    }

    /**
     * Authorize the trader.
     *
     * @param Grid $grid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authorizeTrader(Grid $grid)
    {
        $this->authorize('friendly', $grid->planet);

        if (!$grid->building_id) {
            return $this->createBadRequestJsonResponse();
        }

        if ($grid->building->type != Building::TYPE_TRADER) {
            return $this->createBadRequestJsonResponse();
        }
    }
}
