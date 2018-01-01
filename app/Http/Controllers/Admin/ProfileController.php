<?php

namespace Koodilab\Http\Controllers\Admin;

use DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Admin\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:dashboard');
    }

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('admin.profile.edit');
    }

    /**
     * Update the profile in storage.
     *
     * @param ProfileUpdateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            $request->persist();
        });

        flash()->success(
            trans('messages.success.update')
        );

        return back();
    }
}
