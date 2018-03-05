<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Game\ConstructionManager;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Transformers\ConstructionTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Grid $grid, ConstructionTransformer $transformer)
    {
        $this->authorize('friendly', $grid->planet);

        return $transformer->transform($grid);
    }

    /**
     * Store a newly created construction in storage.
     *
     * @param Grid                $grid
     * @param Building            $building
     * @param ConstructionManager $manager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Grid $grid, Building $building, ConstructionManager $manager)
    {
        $this->authorize('friendly', $grid->planet);

        if ($grid->construction) {
            throw new BadRequestHttpException();
        }

        $building = $grid->constructionBuildings()
            ->keyBy('id')
            ->get($building->id);

        if (! $building) {
            throw new BadRequestHttpException();
        }

        if (! auth()->user()->hasEnergy($building->construction_cost)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($grid, $building, $manager) {
            $manager->create($grid, $building);
        });
    }

    /**
     * Remove the construction from storage.
     *
     * @param Grid                $grid
     * @param ConstructionManager $manager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function destroy(Grid $grid, ConstructionManager $manager)
    {
        $this->authorize('friendly', $grid->planet);

        if (! $grid->construction) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($grid, $manager) {
            $manager->cancel($grid->construction);
        });
    }
}
