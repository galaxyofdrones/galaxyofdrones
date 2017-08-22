<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\User;

trait BelongsToManyUser
{
    /**
     * Get the users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
