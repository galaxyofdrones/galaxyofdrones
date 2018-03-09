<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Transformers\ExpeditionLogTransformer;
use Koodilab\Notifications\ExpeditionLogCreated;

class ExpeditionLogController extends Controller
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
     * Get the expedition logs in json format.
     *
     * @param ExpeditionLogTransformer $transformer
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
