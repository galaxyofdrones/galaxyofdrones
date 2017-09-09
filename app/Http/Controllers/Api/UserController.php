<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;

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
