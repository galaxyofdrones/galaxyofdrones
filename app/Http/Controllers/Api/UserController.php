<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Http\Requests\Api\UserUpdateRequest;
use Koodilab\Models\Planet;
use Koodilab\Models\User;
use Koodilab\Transformers\UserCapitalTransformer;
use Koodilab\Transformers\UserShowTransformer;
use Koodilab\Transformers\UserTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('verified');
        $this->middleware('player');
    }

    /**
     * Show the authenticated user in json format.
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
     * @return mixed|\Illuminate\Http\Response
     */
    public function capital(UserCapitalTransformer $transformer)
    {
        return $transformer->transform(
            auth()->user()
        );
    }

    /**
     * Show the user in json format.
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
     * @throws \Exception|\Throwable
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
     * @throws \Exception|\Throwable
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
     * @throws \Exception|\Throwable
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
