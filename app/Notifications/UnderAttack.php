<?php

namespace App\Notifications;

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
     * @param \App\Models\User $notifiable
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
            ->subject(Lang::get('messages.under_attack.singular'))
            ->line(Lang::get('messages.under_attack.description'))
            ->action(Lang::get('messages.play_now'), route('home'))
            ->line(Lang::get('messages.under_attack.prepare'));
    }
}
