<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;
use Koodilab\Models\Star;
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
     * @param int                      $zoom
     * @param string                   $bounds
     * @param StarFeatureTransformer   $starTransformer
     * @param PlanetFeatureTransformer $planetTransformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function geoJson($zoom, $bounds, StarFeatureTransformer $starTransformer, PlanetFeatureTransformer $planetTransformer)
    {
        $features = collect();

        if ($zoom >= static::GEO_JSON_ZOOM_LEVEL) {
            $bounds = Bounds::fromString($bounds)->scale(1.5);
            $limit = (int) (static::GEO_JSON_LIMIT * config('starmap.ratio'));

            $features = $features->merge(
                $starTransformer->transformCollection(Star::inBounds($bounds)
                    ->limit($limit)
                    ->get())
            );

            $features = $features->merge(
                $planetTransformer->transformCollection(Planet::inBounds($bounds)
                    ->limit(static::GEO_JSON_LIMIT - $limit)
                    ->get())
            );
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
