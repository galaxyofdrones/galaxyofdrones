<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SettingUpdateRequest;
use Illuminate\Support\Facades\DB;

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
