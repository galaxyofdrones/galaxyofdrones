<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Notifications\BattleLogCreated;
use Koodilab\Transformers\BattleLogTransformer;

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
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        $user->deleteNotificationsByType(BattleLogCreated::class);

        return $transformer->transformCollection(
            $user->paginateBattleLogs()
        );
    }
}
