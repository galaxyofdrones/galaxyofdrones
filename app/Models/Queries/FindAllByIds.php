<?php

namespace Koodilab\Models\Queries;

trait FindAllByIds
{
    /**
     * Find all by ids.
     *
     * @param array $ids
     * @param array $columns
     *
     * @return static[]
     */
    public static function findAllByIds($ids, $columns = ['*'])
    {
        return static::whereIn('id', $ids)->get($columns);
    }
}
