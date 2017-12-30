<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Transformers\BattleLogTransformer;

class BattleLogController extends Controller
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
     * Get the battle logs in json format.
     *
     * @param BattleLogTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(BattleLogTransformer $transformer)
    {
        return $transformer->transformCollection(
            auth()->user()->paginateBattleLogs()
        );
    }
}
