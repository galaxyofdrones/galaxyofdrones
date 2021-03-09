<?php

namespace App\Http\Controllers\Api;

use App\Game\MissionManager;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Resource;
use App\Transformers\MissionTransformer;
use App\Transformers\ResourceMissionTransformer;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MissionController extends Controller
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
     * Show the missions in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(ResourceMissionTransformer $resourceMissionTransformer, MissionTransformer $missionTransformer)
    {
        return [
            'solarion' => auth()->user()->solarion,
            'resources' => $resourceMissionTransformer->transformCollection(
                Resource::newModelInstance()->findAllOrderBySortOrder()
            ),
            'missions' => $missionTransformer->transformCollection(
                auth()->user()->findNotExpiredMissions()
            ),
        ];
    }

    /**
     * Store a newly created mission log in storage.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Mission $mission, MissionManager $manager)
    {
        $this->authorize('complete', $mission);

        if (! $mission->isCompletable()) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($mission, $manager) {
            $manager->finish($mission);
        });
    }
}
