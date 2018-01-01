<?php

namespace Koodilab\Http\Controllers\Admin;

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
        $this->middleware('guest', [
            'except' => 'logout',
        ]);

        $this->middleware('can:dashboard', [
            'only' => 'logout',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * {@inheritdoc}
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');

        $credentials['ability'] = 'dashboard';

        return $credentials;
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
        return route('admin_home');
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

        return redirect()->route('admin_login');
    }
}
