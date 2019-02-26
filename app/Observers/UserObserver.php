<?php

namespace Koodilab\Observers;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Events\Dispatcher as Event;
use Koodilab\Events\UserUpdated;
use Koodilab\Models\User;

class UserObserver
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * The event instance.
     *
     * @var Event
     */
    protected $event;

    /**
     * Constructor.
     *
     * @param Auth  $auth
     * @param Event $event
     */
    public function __construct(Auth $auth, Event $event)
    {
        $this->auth = $auth;
        $this->event = $event;
    }

    /**
     * Saving.
     *
     * @param User $user
     */
    public function saving(User $user)
    {
        if ($user->isDirty('capital_id')) {
            $user->last_capital_changed = Carbon::now();
        }
    }

    /**
     * Deleting.
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleting(User $user)
    {
        if ($this->auth->guard()->id() != $user->getKey()) {
            $user->planets->each->update([
                'user_id' => null,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Updated.
     *
     * @param User $user
     */
    public function updated(User $user)
    {
        $this->event->dispatch(
            new UserUpdated($user->id)
        );
    }
}
