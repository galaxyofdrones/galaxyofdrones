<?php

namespace Koodilab\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Koodilab\Models\BattleLog;

class BattleLogCreated extends Notification
{
    use Queueable;

    /**
     * The battle log instance.
     *
     * @var BattleLog
     */
    protected $battleLog;

    /**
     * Constructor.
     *
     * @param BattleLog $battleLog
     */
    public function __construct(BattleLog $battleLog)
    {
        $this->battleLog = $battleLog;
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
            'battle_log_id' => $this->battleLog->id,
        ];
    }
}
