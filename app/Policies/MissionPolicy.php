<?php

namespace App\Policies;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MissionPolicy
{
    use HandlesAuthorization;

    /**
     * Check the complete ability.
     *
     * @return bool
     */
    public function complete(User $user, Mission $mission)
    {
        return $user->id == $mission->user_id;
    }
}
