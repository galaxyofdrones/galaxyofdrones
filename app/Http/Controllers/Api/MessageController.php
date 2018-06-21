<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Api\MessageStoreRequest;
use Koodilab\Models\Transformers\MessageTransformer;
use Koodilab\Notifications\MessageSended;

class MessageController extends Controller
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
     * Get the messages in json format.
     *
     * @param MessageTransformer $transformer
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
     * @param MessageStoreRequest $request
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
