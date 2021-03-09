<?php

namespace App\Models\Queries;

trait PaginatePlanets
{
    /**
     * Paginate the planets.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\Planet[]
     */
    public function paginatePlanets($perPage = 5)
    {
        return $this->planets()
            ->orderByRaw('IFNULL(custom_name, name)')
            ->paginate($perPage);
    }
}
