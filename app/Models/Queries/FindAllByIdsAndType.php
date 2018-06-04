<?php

namespace Koodilab\Models\Queries;

trait FindAllByIdsAndType
{
    /**
     * Find all by ids and type.
     *
     * @param array $ids
     * @param array $types
     * @param array $columns
     *
     * @return static[]
     */
    public static function findAllByIdsAndTypes($ids, $types, $columns = ['*'])
    {
        return static::whereIn('id', $ids)
            ->whereIn('type', $types)
            ->get($columns);
    }
}
