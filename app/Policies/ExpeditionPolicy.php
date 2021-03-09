<?php

namespace App\Policies;

use App\Models\Expedition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
