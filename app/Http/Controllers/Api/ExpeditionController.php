<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Transformers\UnitExpeditionTransformer;
use Koodilab\Models\Unit;

class ExpeditionController extends Controller
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
     * Show the expeditions in json format.
     *
     * @param UnitExpeditionTransformer $unitExpeditionTransformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(UnitExpeditionTransformer $unitExpeditionTransformer)
    {
        return [
            'units' => $unitExpeditionTransformer->transformCollection(
                Unit::newModelInstance()->findAllOrderBySortOrder()
            ),
        ];
    }
}
