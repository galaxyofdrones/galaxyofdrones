<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Construction;
use Koodilab\Models\Grid;
use Koodilab\Models\Transformers\Site\ConstructionTransformer;

class ConstructionController extends Controller
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
     * Show the construction in json format.
     *
     * @param Grid                    $grid
     * @param ConstructionTransformer $transformer
     *
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function index(Grid $grid, ConstructionTransformer $transformer)
    {
        $this->authorize('friendly', $grid->planet);

        return $transformer->transform($grid);
    }

    /**
     * Store a newly created construction in storage.
     *
     * @param Grid     $grid
     * @param Building $building
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Grid $grid, Building $building)
    {
        $this->authorize('friendly', $grid->planet);

        if ($grid->construction) {
            return $this->createBadRequestJsonResponse();
        }

        $constructionBuilding = $grid->constructionBuildings()
            ->keyBy('id')
            ->get($building->id);

        if (!$constructionBuilding) {
            return $this->createBadRequestJsonResponse();
        }

        if (!auth()->user()->hasEnergy($constructionBuilding->construction_cost)) {
            return $this->createBadRequestJsonResponse();
        }

        DB::transaction(function () use ($grid, $constructionBuilding) {
            Construction::createFrom($grid, $constructionBuilding);
        });
    }

    /**
     * Remove the construction from storage.
     *
     * @param Grid $grid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Grid $grid)
    {
        $this->authorize('friendly', $grid->planet);

        if (!$grid->construction) {
            return $this->createBadRequestJsonResponse();
        }

        DB::transaction(function () use ($grid) {
            $grid->construction->cancel();
        });
    }
}
