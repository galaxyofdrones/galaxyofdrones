<?php

namespace Koodilab\Models\Queries;

trait PaginatePlanets
{
    /**
     * Paginate the planets.
     *
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Koodilab\Models\Planet[]
     */
    public function paginatePlanets($perPage = 5)
    {
        return $this->planets()
            ->orderByRaw('IFNULL(custom_name, name)')
            ->paginate($perPage);
    }
}
