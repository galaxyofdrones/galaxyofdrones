<?php

namespace Koodilab\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Koodilab\Models\Expedition;
use Koodilab\Models\User;

class ExpeditionPolicy
{
    use HandlesAuthorization;

    /**
     * Check the complete ability.
     *
     * @return bool
     */
    public function complete(User $user, Expedition $expedition)
    {
        return $user->id == $expedition->user_id;
    }
}
