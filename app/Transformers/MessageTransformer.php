<?php

namespace Koodilab\Transformers;

use Illuminate\Contracts\Auth\Factory as Auth;
use Koodilab\Models\Message;

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
        /** @var \Koodilab\Models\User $user */
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
