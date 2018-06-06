<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;

class MonitorController extends Controller
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
     * Show the monitor in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        return [
            'incoming' => $user->incomingUserAttackMovementCount(),
        ];
    }
}
