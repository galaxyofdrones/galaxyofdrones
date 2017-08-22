<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\User;

trait BelongsToUser
{
    /**
     * Get the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
