<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Resource;
use Koodilab\Models\Transformers\ResourceAvailableTransformer;
use Koodilab\Models\Transformers\UnitAvailableTransformer;
use Koodilab\Models\Unit;

class ResearchController extends Controller
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
     * Show the researches in json format.
     *
     * @param ResourceAvailableTransformer $resourceTransformer
     * @param UnitAvailableTransformer     $unitTransformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(ResourceAvailableTransformer $resourceTransformer, UnitAvailableTransformer $unitTransformer)
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        $resource = $user->findAvailableResource();

        return [
            'resource' => $resource
                ? $resourceTransformer->transform($resource)
                : null,
            'units' => $unitTransformer->transformCollection(
                $user->findAvailableUnits()
            ),
        ];
    }

    public function storeResource(Resource $resource)
    {
    }

    public function destroyResource(Resource $resource)
    {
    }

    public function storeUnit(Unit $unit)
    {
    }

    public function destroyUnit(Unit $unit)
    {
    }
}
