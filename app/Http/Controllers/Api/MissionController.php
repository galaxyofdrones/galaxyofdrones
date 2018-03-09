<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Game\MissionManager;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Mission;
use Koodilab\Models\Resource;
use Koodilab\Models\Transformers\MissionTransformer;
use Koodilab\Models\Transformers\ResourceMissionTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MissionController extends Controller
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
     * Show the missions in json format.
     *
     * @param ResourceMissionTransformer $resourceMissionTransformer
     * @param MissionTransformer         $missionTransformer
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
     * @param Mission        $mission
     * @param MissionManager $manager
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
