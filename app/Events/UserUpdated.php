<?php

namespace Koodilab\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UserUpdated implements ShouldBroadcastNow
{
    /**
     * The user id.
     *
     * @var int
     */
    public $userId;

    /**
     * Constructor.
     *
     * @param int $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'updated';
    }

    /**
     * {@inheritdoc}
     */
    public function broadcastOn()
    {
        return new PrivateChannel("user.{$this->userId}");
    }
}
