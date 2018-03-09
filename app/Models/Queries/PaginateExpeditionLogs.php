<?php

namespace Koodilab\Models\Queries;

trait PaginateExpeditionLogs
{
    /**
     * Paginate the expedition logs.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Koodilab\Models\ExpeditionLog[]
     */
    public function paginateExpeditionLogs($perPage = 5)
    {
        return $this->expeditionLogs()
            ->with('star', 'units')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
