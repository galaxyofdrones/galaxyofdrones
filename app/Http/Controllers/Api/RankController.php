<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use App\Transformers\RankTransformer;

class RankController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('verified');
        $this->middleware('player');
    }

    /**
     * Show the PvE in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function pve(RankTransformer $transformer)
    {
        return $transformer->transformCollection(
            Rank::paginatePveUsers()
        );
    }

    /**
     * Show the PvP in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function pvp(RankTransformer $transformer)
    {
        return $transformer->transformCollection(
            Rank::paginatePvpUsers()
        );
    }
}
