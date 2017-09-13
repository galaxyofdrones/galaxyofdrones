<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Http\Request;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Transformers\Site\PlanetTransformer;

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
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function index(PlanetTransformer $transformer)
    {
        return $transformer->transform(
            auth()->user()->current
        );
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
