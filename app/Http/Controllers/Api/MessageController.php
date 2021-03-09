<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageStoreRequest;
use App\Notifications\MessageSended;
use App\Transformers\MessageTransformer;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
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
     * Get the messages in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(MessageTransformer $transformer)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->deleteNotificationsByType(MessageSended::class);

        return $transformer->transformCollection(
            $user->paginateMessages()
        );
    }

    /**
     * Store a newly created message in storage.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(MessageStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            $request->persist();
        });
    }
}
