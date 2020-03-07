<?php

namespace Koodilab\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Koodilab\Models\Bookmark;
use Koodilab\Models\User;

class BookmarkPolicy
{
    use HandlesAuthorization;

    /**
     * Check the destroy ability.
     *
     * @return bool
     */
    public function destroy(User $user, Bookmark $bookmark)
    {
        return $user->id == $bookmark->user_id;
    }
}
