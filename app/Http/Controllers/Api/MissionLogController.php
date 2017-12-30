<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Transformers\MissionLogTransformer;

class MissionLogController extends Controller
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
     * Get the mission logs in json format.
     *
     * @param MissionLogTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(MissionLogTransformer $transformer)
    {
        return $transformer->transformCollection(
            auth()->user()->paginateMissionLogs()
        );
    }
}
