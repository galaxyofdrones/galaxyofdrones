<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Api\UserUpdateRequest;
use Koodilab\Models\Planet;
use Koodilab\Models\Transformers\UserCapitalTransformer;
use Koodilab\Models\Transformers\UserShowTransformer;
use Koodilab\Models\Transformers\UserTransformer;
use Koodilab\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * Show the capital in json format.
     *
     * @param UserCapitalTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function capital(UserCapitalTransformer $transformer)
    {
        return $transformer->transform(
            auth()->user()
        );
    }

    /**
     * Show the trophy in json format.
     *
     * @param UserShowTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function trophy(UserShowTransformer $transformer)
    {
        return $transformer->transformCollection(
            User::paginateAllStartedOrderByExperience()
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
        if (!$user->isStarted()) {
            throw new BadRequestHttpException();
        }

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
     * Update the capital planet.
     *
     * @param Planet $planet
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function updateCapital(Planet $planet)
    {
        $this->authorize('friendly', $planet);

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        if (!$user->isCapitalChangeable()) {
            throw new BadRequestHttpException();
        }

        $user->update([
            'capital_id' => $planet->id,
        ]);
    }

    /**
     * Update the current planet.
     *
     * @param Planet $planet
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function updateCurrent(Planet $planet)
    {
        $this->authorize('friendly', $planet);

        auth()->user()->update([
            'current_id' => $planet->id,
        ]);
    }
}
