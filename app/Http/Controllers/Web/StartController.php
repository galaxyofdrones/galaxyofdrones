<?php

namespace Koodilab\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Koodilab\Game\ShieldManager;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
use Koodilab\Models\Shield;
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

        return view('start.index', compact('count'));
    }

    /**
     * Store the player start.
     *
     * @param ShieldManager $manager
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ShieldManager $manager)
    {
        $capital = Planet::findFreeCapital();

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        if (! $capital || ! $user->canOccupy($capital)) {
            return back();
        }

        DB::transaction(function () use ($manager, $capital, $user) {
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

            $user->units()->attach(Unit::where('is_unlocked', true)->pluck('id'), [
                'is_researched' => true,
                'quantity' => 0,
            ]);

            $manager->create(
                $capital, Shield::START_EXPIRATION_TIME
            );
        });

        return redirect()->route('home');
    }
}
