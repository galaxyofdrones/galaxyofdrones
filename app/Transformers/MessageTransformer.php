<?php

namespace App\Transformers;

use App\Models\Message;
use Illuminate\Contracts\Auth\Factory as Auth;

class MessageTransformer extends Transformer
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Constructor.
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * {@inheritdoc}
     *
     * @param Message $item
     */
    public function transform($item)
    {
        /** @var \App\Models\User $user */
        $user = $this->auth->guard()->user();

        return [
            'id' => $item->id,
            'message' => nl2br(e($item->message)),
            'created_at' => $item->created_at->toDateTimeString(),
            'sender' => [
                'id' => $item->sender->id,
                'username' => $item->sender->username,
                'is_blocked' => ! empty($user->findByBlocked($item->sender)),
                'is_blocked_by' => ! empty($item->sender->findByBlocked($user)),
            ],
        ];
    }
}
