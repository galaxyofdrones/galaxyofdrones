<?php

namespace Koodilab\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Admin\SettingUpdateRequest;

class SettingController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:dashboard');
        $this->middleware('can:manage,Koodilab\Models\Setting');
    }

    /**
     * Show the form for editing the settings.
     *
     * @param string $translation
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($translation)
    {
        return view(
            'admin.setting.edit',
            compact('translation')
        );
    }

    /**
     * Update the settings in storage.
     *
     * @param SettingUpdateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SettingUpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            $request->persist(setting()->all());
        });

        flash()->success(
            trans('messages.success.update')
        );

        return back();
    }
}
