<?php

namespace Koodilab\Models\Queries;

use Carbon\Carbon;

trait FindNotExpiredExpeditions
{
    /**
     * Find not expired expeditions.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Expedition[]
     */
    public function findNotExpiredExpeditions($columns = ['*'])
    {
        return $this->expeditions()
            ->with('star', 'units')
            ->where('ended_at', '>=', Carbon::now())
            ->orderBy('ended_at')
            ->get($columns);
    }
}
