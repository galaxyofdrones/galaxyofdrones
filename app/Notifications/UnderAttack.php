<?php

namespace Koodilab\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class UnderAttack extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param \Koodilab\Models\User $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->hasVerifiedEmail() && $notifiable->is_notification_enabled
            ? ['mail']
            : [];
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
            ->subject(Lang::trans('messages.under_attack.singular'))
            ->line(Lang::trans('messages.under_attack.description'))
            ->action(Lang::trans('messages.play_now'), route('home'))
            ->line(Lang::trans('messages.under_attack.prepare'));
    }
}
