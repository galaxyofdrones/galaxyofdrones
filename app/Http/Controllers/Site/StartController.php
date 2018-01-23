<?php

namespace Koodilab\Http\Controllers\Site;

use Carbon\Carbon;
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

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        if (!$capital || !$user->canOccupy($capital)) {
            return back();
        }

        DB::transaction(function () use ($capital, $user) {
            $user->occupy($capital);

            $user->update([
                'capital_id' => $capital->id,
                'current_id' => $capital->id,
                'started_at' => Carbon::now(),
            ]);

            $user->resources()->attach(Resource::where('is_unlocked', true)->pluck('id'), [
                'is_researched' => true,
                'quantity' => 0,
            ]);

            $user->units()->sync(
                Unit::where('is_unlocked', true)->pluck('id')
            );
        });

        return redirect()->route('home');
    }
}
