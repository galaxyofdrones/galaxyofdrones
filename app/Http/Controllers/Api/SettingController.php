<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Api\SettingUpdateRequest;

class SettingController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('verified');
        $this->middleware('player');
        $this->middleware('can:viewDeveloperSetting');
    }

    /**
     * Update the settings in storage.
     *
     * @param SettingUpdateRequest $request
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function update(SettingUpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            $request->persist(setting()->all());
        });
    }
}
