<?php

namespace Koodilab\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class UserLoginListener
{
    /**
     * Handle the event.
     *
     * @param Login $event
     */
    public function handle(Login $event)
    {
        $event->user->update([
            'last_login' => Carbon::now(),
        ]);
    }
}
