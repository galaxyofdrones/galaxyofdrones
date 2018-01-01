<?php

namespace Koodilab\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Construction;
use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\Research;
use Koodilab\Models\Star;
use Koodilab\Models\Training;
use Koodilab\Models\Upgrade;
use Koodilab\Models\User;

class HomeController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:dashboard');
    }

    /**
     * Show the admin homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home.index');
    }

    /**
     * Get the overview data in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function overviewData()
    {
        /** @var User $user */
        $user = auth()->user();
        $items = [];

        $items[] = [
            'is_danger' => true,
            'name' => trans('messages.failed_job'),
            'count' => DB::table('failed_jobs')->count(),
        ];

        if ($user->can('manage', Construction::class)) {
            $items[] = [
                'is_success' => true,
                'name' => trans('messages.construction.singular'),
                'count' => Construction::count(),
            ];
        }

        if ($user->can('manage', Upgrade::class)) {
            $items[] = [
                'is_success' => true,
                'name' => trans('messages.upgrade.singular'),
                'count' => Upgrade::count(),
            ];
        }

        if ($user->can('manage', Training::class)) {
            $items[] = [
                'is_success' => true,
                'name' => trans('messages.training.singular'),
                'count' => Training::count(),
            ];
        }

        if ($user->can('manage', Movement::class)) {
            $items[] = [
                'is_info' => true,
                'name' => trans('messages.movement.singular'),
                'count' => Movement::count(),
            ];
        }

        if ($user->can('manage', Research::class)) {
            $items[] = [
                'is_info' => true,
                'name' => trans('messages.research.singular'),
                'count' => Research::count(),
            ];
        }

        if ($user->can('manage', Star::class)) {
            $items[] = [
                'name' => trans('messages.star.singular'),
                'count' => Star::count(),
            ];
        }

        if ($user->can('manage', Planet::class)) {
            $items[] = [
                'name' => trans('messages.planet.singular'),
                'count' => Planet::count(),
            ];
        }

        if ($user->can('manage', User::class)) {
            $items[] = [
                'name' => trans('messages.user.singular'),
                'count' => User::count(),
            ];
        }

        return compact('items');
    }
}
