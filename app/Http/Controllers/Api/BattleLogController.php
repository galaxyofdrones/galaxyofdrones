<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Transformers\BattleLogTransformer;
use Koodilab\Notifications\BattleLogCreated;

class BattleLogController extends Controller
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
     * Get the battle logs in json format.
     *
     * @param BattleLogTransformer $transformer
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
