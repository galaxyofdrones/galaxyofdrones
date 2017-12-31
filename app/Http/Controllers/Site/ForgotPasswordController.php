<?php

namespace Koodilab\Http\Controllers\Site;

use Koodilab\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

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
