<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Transformers\MovementTransformer;

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

    /**
     * Show the monitor movements in json format.
     *
     * @param MovementTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(MovementTransformer $transformer)
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        return [
            'incoming_movements' => $transformer->transformCollection(
                $user->findIncomingUserAttackMovements()
            ),
        ];
    }
}
