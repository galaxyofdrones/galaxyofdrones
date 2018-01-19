<?php

namespace Koodilab\Policies;

use Koodilab\Models\Mission;
use Koodilab\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
