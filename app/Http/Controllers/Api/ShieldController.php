<?php

namespace App\Http\Controllers\Api;

use App\Game\ShieldManager;
use App\Http\Controllers\Controller;
use App\Models\Planet;
use App\Models\Shield;
use App\Transformers\ShieldTransformer;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShieldController extends Controller
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
     * Show the shields in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(ShieldTransformer $transformer)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return [
            'can_store' => $user->hasSolarion(Shield::SOLARION_COUNT),
            'shields' => $transformer->transformCollection(
                $user->findNotExpiredShields()
            ),
        ];
    }

    /**
     * Store a newly created shield in storage.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Planet $planet, ShieldManager $manager)
    {
        $this->authorize('friendly', $planet);

        if (! $planet->user->hasSolarion(Shield::SOLARION_COUNT)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($planet, $manager) {
            $manager->createFromSolarion($planet);
        });
    }
}
