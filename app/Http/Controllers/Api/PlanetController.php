<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Http\Request;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;
use Koodilab\Models\Transformers\PlanetShowTransformer;
use Koodilab\Models\Transformers\PlanetTransformer;

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
     * @param PlanetTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(PlanetTransformer $transformer)
    {
        return $transformer->transform(
            auth()->user()->current
        );
    }

    /**
     * Show the planet in json format.
     *
     * @param Planet                $planet
     * @param PlanetShowTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Planet $planet, PlanetShowTransformer $transformer)
    {
        $this->authorize('friendly', $planet);

        return $transformer->transform($planet);
    }

    /**
     * Update the current name.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function name(Request $request)
    {
        if (!$request->has('name')) {
            return response('Bad Request.', 400);
        }

        auth()->user()->current->update([
            'custom_name' => $request->get('name'),
        ]);
    }
}
