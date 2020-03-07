<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Api\MessageStoreRequest;
use Koodilab\Notifications\MessageSended;
use Koodilab\Transformers\MessageTransformer;

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
        /** @var \Koodilab\Models\User $user */
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
