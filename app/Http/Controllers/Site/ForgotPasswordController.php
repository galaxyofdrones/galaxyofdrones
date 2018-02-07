<?php

namespace Koodilab\Http\Controllers\Site;

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
        return view('site.auth.email');
    }
}
