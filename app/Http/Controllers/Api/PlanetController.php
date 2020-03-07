<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\User;
use Koodilab\Transformers\PlanetAllTransformer;
use Koodilab\Transformers\PlanetShowTransformer;
use Koodilab\Transformers\PlanetTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PlanetController extends Controller
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
     * Show the current planet in json format.
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
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Planet $planet, PlanetShowTransformer $transformer)
    {
        return $transformer->transform($planet);
    }

    /**
     * Update the current name.
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function updateName(Request $request)
    {
        if (! $request->has('name')) {
            throw new BadRequestHttpException();
        }

        $name = strip_tags(
            $request->get('name')
        );

        auth()->user()->current->update([
            'custom_name' => $name,
        ]);
    }

    /**
     * Demolish the building from the grid.
     *
     * @throws \Exception|\Throwable
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
