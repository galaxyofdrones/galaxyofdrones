<?php

use Koodilab\Models\Planet;
use Koodilab\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('user.{id}', function (User $user, $id) {
    return $user->id == $id;
});

Broadcast::channel('planet.{planet}', function (User $user, Planet $planet) {
    return $user->id == $planet->user_id;
});
