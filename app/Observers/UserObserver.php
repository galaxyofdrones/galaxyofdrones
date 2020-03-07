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
     */
    public function __construct(Auth $auth, Event $event)
    {
        $this->auth = $auth;
        $this->event = $event;
    }

    /**
     * Updating.
     */
    public function updating(User $user)
    {
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    }

    /**
     * Saving.
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
     */
    public function updated(User $user)
    {
        $this->event->dispatch(
            new UserUpdated($user->id)
        );
    }
}
