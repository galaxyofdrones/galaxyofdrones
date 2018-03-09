<?php

namespace Koodilab\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExpeditionLogCreated extends Notification
{
    use Queueable;

    /**
     * The expedition log id.
     *
     * @var int
     */
    protected $expeditionLogId;

    /**
     * Constructor.
     *
     * @param int $expeditionLogId
     */
    public function __construct(int $expeditionLogId)
    {
        $this->expeditionLogId = $expeditionLogId;
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
            'expedition_log_id' => $this->expeditionLogId,
        ];
    }
}
