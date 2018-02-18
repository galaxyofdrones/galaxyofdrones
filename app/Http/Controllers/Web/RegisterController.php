<?php

namespace Koodilab\Http\Controllers\Web;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\User;

class RegisterController extends Controller
{
    use RegistersUsers;

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
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * {@inheritdoc}
     */
    public function redirectPath()
    {
        return route('home');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|min:3|max:20|regex:/^[a-zA-Z0-9\.\-_]+$/u|unique:users',
            'email' => 'required|max:255|email|unique:users,email,:id',
            'password' => 'required|min:6|max:255|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \Koodilab\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }
}
