<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Game\TrainingManager;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
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
     * @return mixed|\Illuminate\Http\JsonResponse
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
     * @param Grid            $grid
     * @param Unit            $unit
     * @param TrainingManager $manager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Grid $grid, Unit $unit, TrainingManager $manager)
    {
        $this->authorize('friendly', $grid->planet);
        $this->authorize('building', [$grid->building, Building::TYPE_TRAINER]);

        if ($grid->training) {
            throw new BadRequestHttpException();
        }

        $quantity = $this->quantity();

        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        if (! $user->hasUnit($unit)) {
            throw new BadRequestHttpException();
        }

        if (! $user->hasEnergy($quantity * $unit->train_cost)) {
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

        DB::transaction(function () use ($grid, $unit, $quantity, $manager) {
            $manager->create($grid, $unit, $quantity);
        });
    }

    /**
     * Remove the training from storage.
     *
     * @param Grid            $grid
     * @param TrainingManager $manager
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function destroy(Grid $grid, TrainingManager $manager)
    {
        $this->authorize('friendly', $grid->planet);

        if (! $grid->training) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($grid, $manager) {
            $manager->cancel($grid->training);
        });
    }
}
