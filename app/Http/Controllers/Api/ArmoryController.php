<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Transformers\UnitArmoryTransformer;
use Koodilab\Models\Unit;

class ArmoryController extends Controller
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
     * Show the trainings in json format.
     *
     * @param UnitArmoryTransformer $unitArmoryTransformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(UnitArmoryTransformer $unitArmoryTransformer)
    {
        return [
            'slots' => [],
            'units' => $unitArmoryTransformer->transformCollection(
                Unit::newModelInstance()->findAllOrderBySortOrder()
            ),
        ];
    }
}
