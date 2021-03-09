<?php

namespace App\Http\Controllers\Api;

use App\Game\StorageManager;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Grid;
use App\Models\Resource;
use App\Transformers\ProducerTransformer;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProducerController extends Controller
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
     * Show the producer in json format.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Grid $grid, ProducerTransformer $transformer)
    {
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_PRODUCER]);

        return $transformer->transform($grid);
    }

    /**
     * Store a newly created transmute in storage.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Grid $grid, Resource $resource, StorageManager $manager)
    {
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_PRODUCER]);

        $quantity = $this->quantity();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (! $user->hasResource($resource)) {
            throw new BadRequestHttpException();
        }

        if (! $manager->hasStock(auth()->user()->current, $resource, $quantity)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($resource, $manager, $user, $quantity) {
            $user->incrementEnergy(
                round($quantity * $resource->efficiency)
            );

            $manager->decrementStock(
                auth()->user()->current, $resource, $quantity
            );
        });
    }
}
