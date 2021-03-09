<?php

namespace App\Models\Queries;

trait FindByIdOrUsername
{
    /**
     * Find by id or username.
     *
     * @param mixed $value
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public static function findByIdOrUsername($value, $columns = ['*'])
    {
        return static::where('id', $value)
            ->orWhere('username', $value)
            ->first($columns);
    }
}
