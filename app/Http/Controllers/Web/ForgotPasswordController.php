<?php

namespace Koodilab\Http\Controllers\Web;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Koodilab\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

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
    public function showLinkRequestForm()
    {
        return view('auth.email');
    }
}
