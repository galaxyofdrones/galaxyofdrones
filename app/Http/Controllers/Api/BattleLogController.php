<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\BattleLogCreated;
use App\Transformers\BattleLogTransformer;

class BattleLogController extends Controller
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
     * Get the battle logs in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(BattleLogTransformer $transformer)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->deleteNotificationsByType(BattleLogCreated::class);

        return $transformer->transformCollection(
            $user->paginateBattleLogs()
        );
    }
}
