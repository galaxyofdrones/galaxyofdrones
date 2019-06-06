<?php

namespace Koodilab\Http\Controllers\Web;

use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Web\EmailUpdateRequest;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * {@inheritdoc}
     */
    public function redirectPath()
    {
        return route('home');
    }

    /**
     * Update the email in storage.
     *
     * @param EmailUpdateRequest $request
     *
     * @throws \Exception|\Throwable
     *
     * @return \Illuminate\Http\Response
     */
    public function update(EmailUpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            $request->persist();
        });

        return redirect()->route('verification.resend');
    }
}
