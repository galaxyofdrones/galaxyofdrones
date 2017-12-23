<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Training;
use Koodilab\Models\Transformers\TrainerTransformer;
use Koodilab\Models\Unit;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TrainerController extends Controller
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
     * Show the trainer in json format.
     *
     * @param Grid               $grid
     * @param TrainerTransformer $transformer
     *
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function index(Grid $grid, TrainerTransformer $transformer)
    {
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_TRAINER]);

        return $transformer->transform($grid);
    }

    /**
     * Store a newly created training in storage.
     *
     * @param Request $request
     * @param Grid    $grid
     * @param Unit    $unit
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Request $request, Grid $grid, Unit $unit)
    {
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_TRAINER]);

        if ($grid->training) {
            throw new BadRequestHttpException();
        }

        $quantity = $this->quantity();

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        if (!$user->hasUnit($unit)) {
            throw new BadRequestHttpException();
        }

        if (!$user->hasEnergy($quantity * $unit->train_cost)) {
            throw new BadRequestHttpException();
        }

        if ($grid->planet->free_supply < $quantity * $unit->supply) {
            throw new BadRequestHttpException();
        }

        $grid->building->applyModifiers([
            'level' => $grid->level,
        ]);

        $unit->applyModifiers([
            'train_time_bonus' => $grid->building->train_time_bonus,
        ]);

        DB::transaction(function () use ($grid, $unit, $quantity) {
            Training::createFrom(
                $grid, $unit, $quantity
            );
        });
    }

    /**
     * Remove the training from storage.
     *
     * @param Grid $grid
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function destroy(Grid $grid)
    {
        $this->authorize('friendly', $grid->planet);

        if (!$grid->training) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($grid) {
            $grid->training->cancel();
        });
    }
}
