<?php

namespace Koodilab\Http\Controllers\Web;

use Koodilab\Http\Controllers\Controller;

class HomeController extends Controller
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
     * Show the homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.index');
    }
}
