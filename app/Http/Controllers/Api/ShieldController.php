<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Game\ShieldManager;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;
use Koodilab\Models\Shield;
use Koodilab\Models\Transformers\ShieldTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShieldController extends Controller
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
     * Show the shields in json format.
     *
     * @param ShieldTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(ShieldTransformer $transformer)
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        return [
            'canStore' => $user->hasSolarion(Shield::SOLARION_COUNT),
            'shields' => $transformer->transformCollection(
                $user->findNotExpiredShields()
            ),
        ];
    }

    /**
     * Store a newly created shield in storage.
     *
     * @param Planet        $planet
     * @param ShieldManager $manager
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
