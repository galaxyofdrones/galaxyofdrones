<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
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
}
