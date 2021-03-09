<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

        $request->session()->invalidate();

        flash()->success(
            trans('messages.success.logout')
        );

        return redirect()->route('login');
    }
}
