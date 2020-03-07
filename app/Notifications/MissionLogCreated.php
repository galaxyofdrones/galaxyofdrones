<?php

namespace Koodilab\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MissionLogCreated extends Notification
{
    use Queueable;

    /**
     * The mission log id.
     *
     * @var int
     */
    protected $missionLogId;

    /**
     * Constructor.
     */
    public function __construct(int $missionLogId)
    {
        $this->missionLogId = $missionLogId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'mission_log_id' => $this->missionLogId,
        ];
    }
}
