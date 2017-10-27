<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Transformers\Site\ScoutTransformer;

class ScoutController extends Controller
{
    /**
     * Show the scout in json format.
     *
     * @param ScoutTransformer $transformer
     * @param Grid             $grid
     *
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function index(Grid $grid, ScoutTransformer $transformer)
    {
        $this->authorize('friendly', $grid->planet);

        if (!$grid->building_id) {
            return $this->createBadRequestJsonResponse();
        }

        if ($grid->building->type != Building::TYPE_SCOUT) {
            return $this->createBadRequestJsonResponse();
        }

        return $transformer->transform($grid);
    }
}
