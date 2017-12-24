<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Api\UserUpdateRequest;
use Koodilab\Models\Planet;
use Koodilab\Models\Transformers\UserShowTransformer;
use Koodilab\Models\Transformers\UserTransformer;
use Koodilab\Models\User;

class UserController extends Controller
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
     * Show the authenticated user in json format.
     *
     * @param UserTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(UserTransformer $transformer)
    {
        return $transformer->transform(
            auth()->user()
        );
    }

    /**
     * Show the user in json format.
     *
     * @param User                $user
     * @param UserShowTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(User $user, UserShowTransformer $transformer)
    {
        return $transformer->transform($user);
    }

    /**
     * Update the user in storage.
     *
     * @param UserUpdateRequest $request
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            $request->persist();
        });
    }

    /**
     * Update the current planet.
     *
     * @param Planet $planet
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function current(Planet $planet)
    {
        $this->authorize('friendly', $planet);

        auth()->user()->update([
            'current_id' => $planet->id,
        ]);
    }
}
