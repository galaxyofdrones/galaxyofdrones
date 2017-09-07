<?php

namespace Koodilab\Http\Controllers\Api;

use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Planet;
use Koodilab\Models\Star;
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
        $features = [];

        if ($zoom >= static::GEO_JSON_ZOOM_LEVEL) {
            $bounds = Bounds::fromString($bounds)->scale(1.5);
            $limit = (int) (static::GEO_JSON_LIMIT * config('starmap.ratio'));

            /** @var Star[] $stars */
            $stars = Star::inBounds($bounds)
                ->limit($limit)
                ->get(['id', 'x', 'y', 'name']);

            foreach ($stars as $star) {
                $features[] = $star->toFeature();
            }

            /** @var Planet[] $planets */
            $planets = Planet::inBounds($bounds)
                ->limit(static::GEO_JSON_LIMIT - $limit)
                ->get(['id', 'user_id', 'x', 'y', 'name', 'custom_name', 'size']);

            foreach ($planets as $planet) {
                $features[] = $planet->toFeature();
            }
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
