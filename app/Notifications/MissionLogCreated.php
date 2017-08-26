<?php

namespace Koodilab\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Koodilab\Models\MissionLog;

class MissionLogCreated extends Notification
{
    use Queueable;

    /**
     * The mission log instance.
     *
     * @var MissionLog
     */
    protected $missionLog;

    /**
     * Constructor.
     *
     * @param MissionLog $missionLog
     */
    public function __construct(MissionLog $missionLog)
    {
        $this->missionLog = $missionLog;
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
            'mission_log' => $this->missionLog->id,
        ];
    }
}
