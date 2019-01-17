<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;
use Koodilab\Models\Star;

class StatusController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Show the status in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return [
            'starmap' => [
                'star_count' => Star::count(),
                'started_at' => Star::first()->created_at,
                'planet' => [
                    'free_count' => Planet::whereNull('user_id')->count(),
                    'occupied_count' => Planet::whereNotNull('user_id')->count(),
                    'starter_count' => Planet::starter()->count(),
                ],
            ],
        ];
    }
}
