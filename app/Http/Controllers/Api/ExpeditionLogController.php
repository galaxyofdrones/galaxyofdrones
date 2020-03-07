<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Notifications\ExpeditionLogCreated;
use Koodilab\Transformers\ExpeditionLogTransformer;

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
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        $user->deleteNotificationsByType(ExpeditionLogCreated::class);

        return $transformer->transformCollection(
            $user->paginateExpeditionLogs()
        );
    }
}
