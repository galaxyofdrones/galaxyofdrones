<?php

namespace Koodilab\Http\Controllers\Site;

use Koodilab\Http\Controllers\Controller;

class StarmapController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('player');
    }

    /**
     * Show the starmap.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('site.starmap.index');
    }
}
