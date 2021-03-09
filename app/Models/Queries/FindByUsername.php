<?php

namespace App\Models\Queries;

trait FindByUsername
{
    /**
     * Find by username.
     *
     * @param string $username
     * @param array  $columns
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public static function findByUsername($username, $columns = ['*'])
    {
        return static::where('username', $username)
            ->whereNotNull('started_at')
            ->first($columns);
    }
}
