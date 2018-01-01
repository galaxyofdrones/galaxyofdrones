<?php

namespace Koodilab\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Koodilab\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Check the manage ability.
     *
     * @param User $user
     *
     * @return bool
     */
    public function manage(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Check the edit ability.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function edit(User $user, User $model)
    {
        return $user->isAdmin() && $user->canGiveRole($model->role);
    }
}
