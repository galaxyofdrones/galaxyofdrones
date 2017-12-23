<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Mission;
use Koodilab\Models\Transformers\TraderTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_TRADER]);

        return $transformer->transform($grid);
    }

    /**
     * Store a newly created trading in storage.
     *
     * @param Grid    $grid
     * @param Mission $mission
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Grid $grid, Mission $mission)
    {
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_TRADER]);

        if ($grid->planet_id != $mission->planet_id) {
            throw new BadRequestHttpException();
        }

        if (!$mission->isCompletable()) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($mission) {
            $mission->finish();
        });
    }
}
