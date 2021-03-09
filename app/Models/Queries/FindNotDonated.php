<?php

namespace App\Models\Queries;

trait FindNotDonated
{
    /**
     * Find not donated.
     *
     * @param string $email
     * @param array  $columns
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public static function findNotDonated($email, $columns = ['*'])
    {
        return static::where('email', $email)
            ->whereNotNull('started_at')
            ->whereNull('donated_at')
            ->first($columns);
    }
}
