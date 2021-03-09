<?php

namespace App\Http\Controllers\Api;

use App\Game\ExpeditionManager;
use App\Http\Controllers\Controller;
use App\Models\Expedition;
use App\Models\Unit;
use App\Transformers\ExpeditionTransformer;
use App\Transformers\UnitExpeditionTransformer;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExpeditionController extends Controller
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
     * Show the expeditions in json format.
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
     * @throws \Exception|\Throwable
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
