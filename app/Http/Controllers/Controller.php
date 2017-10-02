<?php

namespace Koodilab\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create a bad request json response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createBadRequestJsonResponse()
    {
        return response()->json(['message' => 'Bad Request.'], 400);
    }
}
