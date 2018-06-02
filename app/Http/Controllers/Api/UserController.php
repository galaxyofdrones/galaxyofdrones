<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Api\UserUpdateRequest;
use Koodilab\Models\Planet;
use Koodilab\Models\Transformers\UserCapitalTransformer;
use Koodilab\Models\Transformers\UserShowTransformer;
use Koodilab\Models\Transformers\UserTransformer;
use Koodilab\Models\Transformers\UserTrophyTransformer;
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
     * Show the trophy PvE in json format.
     *
     * @param UserTrophyTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function trophyPve(UserTrophyTransformer $transformer)
    {
        return $transformer->transformCollection(
            User::paginateAllStartedOrderByPve()
        );
    }

    /**
     * Show the trophy PvP in json format.
     *
     * @param UserTrophyTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function trophyPvp(UserTrophyTransformer $transformer)
    {
        return $transformer->transformCollection(
            User::paginateAllStartedOrderByPvp()
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
        if (! $user->isStarted()) {
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

        if (! $planet->user->canChangeCapital()) {
            throw new BadRequestHttpException();
        }

        if ($planet->user->capital->incomingCapitalMovementCount()) {
            throw new BadRequestHttpException();
        }

        $planet->user->update([
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

        $planet->user->update([
            'current_id' => $planet->id,
        ]);
    }
}
