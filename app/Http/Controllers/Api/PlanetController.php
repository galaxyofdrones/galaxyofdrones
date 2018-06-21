<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\Transformers\PlanetAllTransformer;
use Koodilab\Models\Transformers\PlanetShowTransformer;
use Koodilab\Models\Transformers\PlanetTransformer;
use Koodilab\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PlanetController extends Controller
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
     * Show the current planet in json format.
     *
     * @param PlanetTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(PlanetTransformer $transformer)
    {
        return $transformer->transform(
            auth()->user()->current
        );
    }

    /**
     * Show the all planet in json format.
     *
     * @param User                 $user
     * @param PlanetAllTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function all(User $user, PlanetAllTransformer $transformer)
    {
        return $transformer->transformCollection(
            $user->paginatePlanets()
        );
    }

    /**
     * Show the capital planet in json format.
     *
     * @param PlanetShowTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function capital(PlanetShowTransformer $transformer)
    {
        return $transformer->transform(
            auth()->user()->capital
        );
    }

    /**
     * Show the planet in json format.
     *
     * @param Planet                $planet
     * @param PlanetShowTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Planet $planet, PlanetShowTransformer $transformer)
    {
        return $transformer->transform($planet);
    }

    /**
     * Update the current name.
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function updateName(Request $request)
    {
        if (! $request->has('name')) {
            throw new BadRequestHttpException();
        }

        auth()->user()->current->update([
            'custom_name' => $request->get('name'),
        ]);
    }

    /**
     * Demolish the building from the grid.
     *
     * @param Grid $grid
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function demolish(Grid $grid)
    {
        $this->authorize('friendly', $grid->planet);

        if (! $grid->building_id) {
            throw new BadRequestHttpException();
        }

        if ($grid->upgrade) {
            throw new BadRequestHttpException();
        }

        if ($grid->training) {
            throw new BadRequestHttpException();
        }

        if ($grid->planet->isCapital() && $grid->building->type == Building::TYPE_CENTRAL) {
            throw new BadRequestHttpException();
        }

        DB::transaction(function () use ($grid) {
            $grid->demolishBuilding();

            if ($grid->building->type != Building::TYPE_CENTRAL) {
                event(
                    new PlanetUpdated($grid->planet_id)
                );
            }
        });
    }
}
