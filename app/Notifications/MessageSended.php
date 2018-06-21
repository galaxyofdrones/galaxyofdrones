<?php

namespace Koodilab\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MessageSended extends Notification
{
    use Queueable;

    /**
     * The message id.
     *
     * @var int
     */
    protected $messageId;

    /**
     * Constructor.
     *
     * @param int $messageId
     */
    public function __construct(int $messageId)
    {
        $this->messageId = $messageId;
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
            'message_id' => $this->messageId,
        ];
    }
}
