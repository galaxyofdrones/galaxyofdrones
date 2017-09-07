<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Transformers\Site\CurrentPlanetTransformer;

class PlanetController extends Controller
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
     * Show the current planet in json format.
     *
     * @param CurrentPlanetTransformer $transformer
     *
     * @return mixed
     */
    public function current(CurrentPlanetTransformer $transformer)
    {
        return $transformer->transform(
            auth()->user()->current
        );
    }
}
