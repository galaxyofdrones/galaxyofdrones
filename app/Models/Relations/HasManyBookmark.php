<?php

namespace Koodilab\Models\Relations;

use Koodilab\Models\Bookmark;

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
