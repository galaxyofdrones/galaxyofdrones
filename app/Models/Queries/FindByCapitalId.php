<?php

namespace Koodilab\Models\Queries;

trait FindByCapitalId
{
    /**
     * Find by capital id.
     *
     * @param int   $capitalId
     * @param array $columns
     *
     * @return static
     */
    public static function findByCapitalId($capitalId, $columns = ['*'])
    {
        return static::where('capital_id', $capitalId)->first($columns);
    }
}
