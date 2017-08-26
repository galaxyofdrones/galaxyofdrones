<?php

namespace Koodilab\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PlanetUpdated implements ShouldBroadcastNow
{
    /**
     * The planet id.
     *
     * @var int
     */
    public $planetId;

    /**
     * Constructor.
     *
     * @param int $planetId
     */
    public function __construct($planetId)
    {
        $this->planetId = $planetId;
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
        return new PrivateChannel("planet.{$this->planetId}");
    }
}
