<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Research;
use Koodilab\Models\Transformers\ResourceAvailableTransformer;
use Koodilab\Models\Transformers\UnitAvailableTransformer;
use Koodilab\Models\Unit;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ResearchController extends Controller
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
     * Show the researches in json format.
     *
     * @param ResourceAvailableTransformer $resourceTransformer
     * @param UnitAvailableTransformer     $unitTransformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(ResourceAvailableTransformer $resourceTransformer, UnitAvailableTransformer $unitTransformer)
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        $resource = $user->findAvailableResource();

        return [
            'resource' => $resource
                ? $resourceTransformer->transform($resource)
                : null,
            'units' => $unitTransformer->transformCollection(
                $user->findAvailableUnits()
            ),
        ];
    }

    /**
     * Store a newly created research in storage.
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeResource()
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        $resource = $user->findAvailableResource();

        if (! $resource) {
            throw new BadRequestHttpException();
        }

        if ($resource->findResearchByUser($user)) {
            throw new BadRequestHttpException();
        }

        if (! $user->hasEnergy($resource->research_cost)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($resource) {
            Research::createFrom($resource);
        });
    }

    /**
     * Store a newly created research in storage.
     *
     * @param Unit $unit
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function storeUnit(Unit $unit)
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        $units = $user->findAvailableUnits();

        if (! $units->contains($unit)) {
            throw new BadRequestHttpException();
        }

        if ($unit->findResearchByUser($user)) {
            throw new BadRequestHttpException();
        }

        if (! $user->hasEnergy($unit->research_cost)) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($unit) {
            Research::createFrom($unit);
        });
    }

    /**
     * Remove the research from storage.
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function destroyResource()
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        $resource = $user->findAvailableResource();

        if (! $resource) {
            throw new BadRequestHttpException();
        }

        $research = $resource->findResearchByUser($user);

        if (! $research) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($research) {
            $research->cancel();
        });
    }

    /**
     * Remove the research from storage.
     *
     * @param Unit $unit
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function destroyUnit(Unit $unit)
    {
        $research = $unit->findResearchByUser(
            auth()->user()
        );

        if (! $research) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($research) {
            $research->cancel();
        });
    }
}
