<?php

namespace Koodilab\Http\Controllers\Site;

use Illuminate\Http\Request;
use Koodilab\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * {@inheritdoc}
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('site.auth.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function redirectPath()
    {
        return route('home');
    }
}
