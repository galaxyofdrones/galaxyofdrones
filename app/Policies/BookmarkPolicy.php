<?php

namespace Koodilab\Policies;

use Koodilab\Models\Bookmark;
use Koodilab\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookmarkPolicy
{
    use HandlesAuthorization;

    /**
     * Check the manage ability.
     *
     * @param User     $user
     * @param Bookmark $bookmark
     *
     * @return bool
     */
    public function destroy(User $user, Bookmark $bookmark)
    {
        return $user->id == $bookmark->user_id;
    }
}
