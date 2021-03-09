<?php

namespace App\Policies;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
