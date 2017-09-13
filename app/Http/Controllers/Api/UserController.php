<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;
use Koodilab\Models\Transformers\Site\UserTransformer;

class UserController extends Controller
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
     * Show the authenticated user in json format.
     *
     * @param UserTransformer $transformer
     *
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function index(UserTransformer $transformer)
    {
        return $transformer->transform(
            auth()->user()
        );
    }

    /**
     * Update the current planet.
     *
     * @param Planet $planet
     *
     * @return \Illuminate\Http\Response
     */
    public function current(Planet $planet)
    {
        $this->authorize('friendly', $planet);

        auth()->user()->update([
            'current_id' => $planet->id,
        ]);
    }
}
