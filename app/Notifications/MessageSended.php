<?php

namespace Koodilab\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

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
     * @param \Koodilab\Models\User $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['database', 'broadcast'];

        if ($notifiable->hasVerifiedEmail() && $notifiable->is_notification_enabled) {
            return array_merge($via, [
                'mail',
            ]);
        }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(Lang::get('messages.message.new'))
            ->line(Lang::get('messages.message.description'))
            ->action(Lang::get('messages.play_now'), route('home'))
            ->line(Lang::get('messages.message.read'));
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
