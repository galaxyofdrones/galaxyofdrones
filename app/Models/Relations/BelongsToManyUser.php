<?php

namespace App\Models\Relations;

use App\Models\User;

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
