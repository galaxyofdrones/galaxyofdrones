<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\ExpeditionLogCreated;
use App\Transformers\ExpeditionLogTransformer;

class ExpeditionLogController extends Controller
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
     * Get the expedition logs in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(ExpeditionLogTransformer $transformer)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->deleteNotificationsByType(ExpeditionLogCreated::class);

        return $transformer->transformCollection(
            $user->paginateExpeditionLogs()
        );
    }
}
