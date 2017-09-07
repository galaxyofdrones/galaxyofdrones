<?php

namespace Koodilab\Http\Controllers\Site;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;

class StartController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('recruit');
    }

    /**
     * Show the start page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = Planet::starter()->count();

        return view('site.start.index', compact('count'));
    }

    /**
     * Store the player start.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $capital = Planet::findFreeCapital();

        if (!$capital) {
            return back();
        }

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        DB::transaction(function () use ($capital, $user) {
            $user->occupy($capital);

            $user->capital()->associate($capital);
            $user->current()->associate($capital);
            $user->save();

            $user->resources()->sync(
                Resource::where('is_unlocked', true)->pluck('id')
            );

            $user->units()->sync(
                Unit::where('is_unlocked', true)->pluck('id')
            );
        });

        return redirect()->route('home');
    }
}
