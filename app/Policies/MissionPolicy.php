<?php

namespace Koodilab\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Koodilab\Models\Mission;
use Koodilab\Models\User;

class MissionPolicy
{
    use HandlesAuthorization;

    /**
     * Check the complete ability.
     *
     * @param User    $user
     * @param Mission $mission
     *
     * @return bool
     */
    public function complete(User $user, Mission $mission)
    {
        return $user->id == $mission->user_id;
    }
}
