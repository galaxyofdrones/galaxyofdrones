<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Star;
use Koodilab\Transformers\StarShowTransformer;

class StarController extends Controller
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
     * Show the star in json format.
     *
     * @param Star                $star
     * @param StarShowTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Star $star, StarShowTransformer $transformer)
    {
        return $transformer->transform($star);
    }
}
