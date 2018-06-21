<?php

namespace Koodilab\Models\Queries;

trait PaginateMessages
{
    /**
     * Paginate the messages.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Koodilab\Models\MissionLog[]
     */
    public function paginateMessages($perPage = 5)
    {
        return $this->messages()
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
