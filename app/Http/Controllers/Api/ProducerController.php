<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Resource;
use Koodilab\Models\Transformers\ProducerTransformer;

class ProducerController extends Controller
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
     * Show the producer in json format.
     *
     * @param Grid                $grid
     * @param ProducerTransformer $transformer
     *
     * @return \Illuminate\Http\JsonResponse|array
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
     * @param Request  $request
     * @param Grid     $grid
     * @param resource $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Grid $grid, Resource $resource)
    {
        $this->authorize('friendly', $grid->planet);

        $this->authorize('building', [$grid->building, Building::TYPE_PRODUCER]);

        $quantity = (int) $request->get('quantity', 0);

        if ($quantity <= 0) {
            return $this->createBadRequestJsonResponse();
        }

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        if (!$user->hasResource($resource)) {
            return $this->createBadRequestJsonResponse();
        }

        $stock = $grid->planet->findStock($resource);

        if (!$stock->hasQuantity($quantity)) {
            return $this->createBadRequestJsonResponse();
        }

        DB::transaction(function () use ($resource, $user, $stock, $quantity) {
            $user->incrementEnergy(
                round($quantity * $resource->efficiency)
            );

            $stock->decrementQuantity($quantity);
        });
    }
}
