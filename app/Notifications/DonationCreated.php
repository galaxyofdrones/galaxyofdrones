<?php

namespace Koodilab\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class DonationCreated extends Notification
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
            ->subject(Lang::get('messages.donation.singular'))
            ->line(Lang::get('messages.donation.reward'))
            ->action(Lang::get('messages.play_now'), route('home'))
            ->line(Lang::get('messages.donation.future'));
    }
}
