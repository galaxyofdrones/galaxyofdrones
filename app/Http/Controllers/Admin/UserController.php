<?php

namespace Koodilab\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Admin\UserStoreRequest;
use Koodilab\Http\Requests\Admin\UserUpdateRequest;
use Koodilab\Models\Providers\UserProvider;
use Koodilab\Models\User;

class UserController extends Controller
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
     * Show the users table view.
     *
     * @param UserProvider $provider
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserProvider $provider)
    {
        $this->authorize('manage', User::class);

        return view(
            'admin.user.index', $provider->viewData()
        );
    }

    /**
     * Get the users in json format.
     *
     * @param UserProvider $provider
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(UserProvider $provider)
    {
        $this->authorize('manage', User::class);

        return $provider->data();
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', User::class);

        $user = new User();

        return view(
            'admin.user.form', compact('user')
        );
    }

    /**
     * Store a newly created user in storage.
     *
     * @param UserStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $this->authorize('manage', User::class);

        DB::transaction(function () use ($request) {
            $request->persist();
        });

        flash()->success(
            trans('messages.success.create')
        );

        return back();
    }

    /**
     * Show the form for editing the user.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('edit', $user);

        return view(
            'admin.user.form', compact('user')
        );
    }

    /**
     * Update the user in storage.
     *
     * @param UserUpdateRequest $request
     * @param User              $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('edit', $user);

        DB::transaction(function () use ($request) {
            $request->persist();
        });

        flash()->success(
            trans('messages.success.update')
        );

        return back();
    }

    /**
     * Remove the users from storage.
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->authorize('destroy', User::class);

        DB::transaction(function () use ($request) {
            User::destroy(
                $request->get('ids', [])
            );
        });
    }
}
