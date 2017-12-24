<?php

namespace Koodilab\Http\Controllers\Api;

use DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Api\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('player');
    }

    /**
     * Update the profile in storage.
     *
     * @param ProfileUpdateRequest $request
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            $request->persist();
        });
    }
}
