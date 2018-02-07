<?php

namespace Koodilab\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get the quantity.
     *
     * @return int
     */
    protected function quantity()
    {
        $quantity = (int) request('quantity', 0);

        if ($quantity <= 0) {
            throw new BadRequestHttpException();
        }

        return $quantity;
    }

    /**
     * Get the quantities.
     *
     * @return \Illuminate\Support\Collection|int[]
     */
    protected function quantities()
    {
        $quantities = collect(request('quantity', []))
            ->filter()
            ->map('intval');

        if ($quantities->isEmpty()) {
            throw new BadRequestHttpException();
        }

        return $quantities;
    }
}
