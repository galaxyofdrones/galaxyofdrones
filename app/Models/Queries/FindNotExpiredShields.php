<?php

namespace Koodilab\Models\Queries;

use Carbon\Carbon;

trait FindNotExpiredShields
{
    /**
     * Find not expired shields.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Shield[]
     */
    public function findNotExpiredShields($columns = ['*'])
    {
        return $this->shields()
            ->with('planet')
            ->where('ended_at', '>=', Carbon::now())
            ->orderBy('ended_at')
            ->get($columns);
    }
}
