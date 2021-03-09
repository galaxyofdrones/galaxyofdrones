<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Planet;
use App\Models\Star;

class StatusController extends Controller
{
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
                'started_at' => Star::first()->created_at->toDateTimeString(),
                'planet' => [
                    'free_count' => Planet::whereNull('user_id')->count(),
                    'occupied_count' => Planet::whereNotNull('user_id')->count(),
                    'starter_count' => Planet::starter()->count(),
                ],
            ],
        ];
    }
}
