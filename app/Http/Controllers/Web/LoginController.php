<?php

namespace Koodilab\Http\Controllers\Web;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Koodilab\Http\Controllers\Controller;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * {@inheritdoc}
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * {@inheritdoc}
     */
    public function username()
    {
        return 'username_or_email';
    }

    /**
     * {@inheritdoc}
     */
    public function redirectPath()
    {
        return route('home');
    }

    /**
     * {@inheritdoc}
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();
        $request->session()->regenerate();

        flash()->success(trans('messages.success.logout'));

        return redirect()->route('login');
    }
}
