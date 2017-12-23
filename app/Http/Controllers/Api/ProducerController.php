<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Resource;
use Koodilab\Models\Transformers\ProducerTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * @param Grid     $grid
     * @param resource $resource
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Grid $grid, Resource $resource)
    {
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_PRODUCER]);

        $quantity = $this->quantity();

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        if (!$user->hasResource($resource)) {
            throw new BadRequestHttpException();
        }

        $stock = $grid->planet->findStock($resource);

        if (!$stock->hasQuantity($quantity)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($resource, $user, $stock, $quantity) {
            $user->incrementEnergy(
                round($quantity * $resource->efficiency)
            );

            $stock->decrementQuantity($quantity);
        });
    }
}
