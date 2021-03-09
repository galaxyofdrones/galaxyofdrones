<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\MovementTransformer;

class MonitorController extends Controller
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
     * Show the monitor in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return [
            'incoming' => $user->incomingUserAttackMovementCount(),
        ];
    }

    /**
     * Show the monitor movements in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(MovementTransformer $transformer)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return [
            'incoming_movements' => $transformer->transformCollection(
                $user->findIncomingUserAttackMovements()
            ),
        ];
    }
}
