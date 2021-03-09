<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\MissionLogCreated;
use App\Transformers\MissionLogTransformer;

class MissionLogController extends Controller
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
     * Get the mission logs in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(MissionLogTransformer $transformer)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->deleteNotificationsByType(MissionLogCreated::class);

        return $transformer->transformCollection(
            $user->paginateMissionLogs()
        );
    }
}
