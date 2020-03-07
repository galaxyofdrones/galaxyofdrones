<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Game\UpgradeManager;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Grid;
use Koodilab\Models\Upgrade;
use Koodilab\Transformers\UpgradeTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpgradeController extends Controller
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
     * Show the upgrade in json format.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Grid $grid, UpgradeTransformer $transformer)
    {
        $this->authorize('friendly', $grid->planet);

        return $transformer->transform($grid);
    }

    /**
     * Show the cost of all upgrades in json format.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function indexAll()
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        return [
            'has_solarion' => $user->hasSolarion(Upgrade::SOLARION_COUNT),
            'upgrade_count' => $user->current->upgrades()->count(),
            'upgrade_cost' => $user->current->upgradeCost(),
        ];
    }

    /**
     * Store a newly created upgrade in storage.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Grid $grid, UpgradeManager $manager)
    {
        $this->authorize('friendly', $grid->planet);

        if (! $grid->building_id) {
            throw new BadRequestHttpException();
        }

        if ($grid->upgrade) {
            throw new BadRequestHttpException();
        }

        $building = $grid->upgradeBuilding();

        if (! $building) {
            throw new BadRequestHttpException();
        }

        if (! auth()->user()->hasEnergy($building->construction_cost)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($grid, $manager) {
            $manager->create($grid);
        });
    }

    /**
     * Store the newly created upgrades in storage.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeAll(UpgradeManager $manager)
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        $upgradeCost = $user->current->upgradeCost();

        if (! $upgradeCost || ! $user->hasEnergy($upgradeCost)) {
            throw new BadRequestHttpException();
        }

        if (! $user->hasSolarion(Upgrade::SOLARION_COUNT)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($manager, $user) {
            $manager->createAll($user);
        });
    }

    /**
     * Remove the upgrade from storage.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function destroy(Grid $grid, UpgradeManager $manager)
    {
        $this->authorize('friendly', $grid->planet);

        if (! $grid->upgrade) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($grid, $manager) {
            $manager->cancel($grid->upgrade);
        });
    }
}
