<?php

namespace Koodilab\Http\Controllers\Site;

use Koodilab\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('site.home.index');
    }
}
