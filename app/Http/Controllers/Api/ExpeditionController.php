<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Game\ExpeditionManager;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Expedition;
use Koodilab\Models\Transformers\ExpeditionTransformer;
use Koodilab\Models\Transformers\UnitExpeditionTransformer;
use Koodilab\Models\Unit;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExpeditionController extends Controller
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
     * Show the expeditions in json format.
     *
     * @param UnitExpeditionTransformer $unitExpeditionTransformer
     * @param ExpeditionTransformer     $expeditionTransformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(UnitExpeditionTransformer $unitExpeditionTransformer, ExpeditionTransformer $expeditionTransformer)
    {
        return [
            'units' => $unitExpeditionTransformer->transformCollection(
                Unit::newModelInstance()->findAllOrderBySortOrder()
            ),
            'expeditions' => $expeditionTransformer->transformCollection(
                auth()->user()->findNotExpiredExpeditions()
            ),
        ];
    }

    /**
     * Store a newly created expedition log in storage.
     *
     * @param Expedition        $expedition
     * @param ExpeditionManager $manager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Expedition $expedition, ExpeditionManager $manager)
    {
        $this->authorize('complete', $expedition);

        if (! $expedition->isCompletable()) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($expedition, $manager) {
            $manager->finish($expedition);
        });
    }
}
