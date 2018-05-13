<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Collection;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;
use Koodilab\Models\Star;
use Koodilab\Models\Transformers\ExpeditionFeatureTransformer;
use Koodilab\Models\Transformers\MovementFeatureTransformer;
use Koodilab\Models\Transformers\MovementUnitFeatureTransformer;
use Koodilab\Models\Transformers\PlanetFeatureTransformer;
use Koodilab\Models\Transformers\StarFeatureTransformer;
use Koodilab\Support\Bounds;

class StarmapController extends Controller
{
    /**
     * The geo json limit.
     *
     * @var int
     */
    const GEO_JSON_LIMIT = 1024;

    /**
     * The geo json zoom level.
     *
     * @var int
     */
    const GEO_JSON_ZOOM_LEVEL = 7;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('player');
    }

    /**
     * Get the geo json data.
     *
     * @param int    $zoom
     * @param string $bounds
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function geoJson($zoom, $bounds)
    {
        $features = collect();

        if ($zoom >= static::GEO_JSON_ZOOM_LEVEL) {
            $bounds = Bounds::fromString($bounds)->scale(1.5);
            $limit = (int) (static::GEO_JSON_LIMIT * config('starmap.ratio'));

            $features = $features->merge(
                app(StarFeatureTransformer::class)->transformCollection(Star::inBounds($bounds)
                    ->limit($limit)
                    ->get())
            );

            $features = $features->merge(
                app(PlanetFeatureTransformer::class)->transformCollection(Planet::inBounds($bounds)
                    ->limit(static::GEO_JSON_LIMIT - $limit)
                    ->get())
            );

            /** @var \Koodilab\Models\User $user */
            $user = auth()->user();

            $incomingMovements = $user->current->findIncomingMovements();
            $outgoingMovements = $user->current->findOutgoingMovements();

            $movementFeatureTransformer = app(MovementFeatureTransformer::class);
            $movementUnitFeatureTransformer = app(MovementUnitFeatureTransformer::class);

            $features = $features->merge(
                $movementFeatureTransformer->transformCollection(
                    Collection::make($incomingMovements)
                )
            );

            $features = $features->merge(
                $movementUnitFeatureTransformer->transformCollection(
                    $incomingMovements
                )
            );

            $features = $features->merge(
                $movementFeatureTransformer->transformCollection(
                    Collection::make($outgoingMovements)
                )
            );

            $features = $features->merge(
                $movementUnitFeatureTransformer->transformCollection(
                    $outgoingMovements
                )
            );

            $features = $features->merge(
                app(ExpeditionFeatureTransformer::class)->transformCollection(
                    $user->findNotExpiredExpeditions()
                )
            );
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
