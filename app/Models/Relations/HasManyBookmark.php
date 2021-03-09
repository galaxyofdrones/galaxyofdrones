<?php

namespace App\Models\Relations;

use App\Models\Bookmark;

trait HasManyBookmark
{
    /**
     * Get the bookmarks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
