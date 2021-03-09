<?php

namespace App\Http\Controllers\Api;

use App\Game\TrainingManager;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Grid;
use App\Models\Unit;
use App\Transformers\TrainerTransformer;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TrainerController extends Controller
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
     * Show the trainer in json format.
     *
     * @throws \Exception|\Throwable
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
     * @throws \Exception|\Throwable
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

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (! $user->hasUnit($unit)) {
            throw new BadRequestHttpException();
        }

        $unit->applyModifiers([
            'train_time_bonus' => $grid->building->train_time_bonus,
            'train_cost_penalty' => $grid->planet->user->penalty_rate,
        ]);

        if (! $user->hasEnergy($quantity * $unit->train_cost)) {
            throw new BadRequestHttpException();
        }

        if ($grid->planet->free_supply < $quantity * $unit->supply) {
            throw new BadRequestHttpException();
        }

        $grid->building->applyModifiers([
            'level' => $grid->level,
        ]);

        DB::transaction(function () use ($grid, $unit, $quantity, $manager) {
            $manager->create($grid, $unit, $quantity);
        });
    }

    /**
     * Remove the training from storage.
     *
     * @throws \Exception|\Throwable
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
