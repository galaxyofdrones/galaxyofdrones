<?php

namespace Koodilab\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BattleLogCreated extends Notification
{
    use Queueable;

    /**
     * The battle log id.
     *
     * @var int
     */
    protected $battleLogId;

    /**
     * Constructor.
     *
     * @param int $battleLogId
     */
    public function __construct($battleLogId)
    {
        $this->battleLogId = $battleLogId;
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
            'battle_log_id' => $this->battleLogId,
        ];
    }
}
