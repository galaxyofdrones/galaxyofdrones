<?php

namespace App\Models\Queries;

trait FindByType
{
    /**
     * Find by type.
     *
     * @param int   $type
     * @param array $columns
     *
     * @return static
     */
    public static function findByType($type, $columns = ['*'])
    {
        return static::where('type', $type)->first($columns);
    }
}
