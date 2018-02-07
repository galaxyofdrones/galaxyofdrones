<?php

namespace Koodilab\Starmap;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\DatabaseManager;
use Koodilab\Contracts\Starmap\Generator as GeneratorContract;
use Koodilab\Contracts\Starmap\NameGenerator;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
use Koodilab\Models\Star;
use Koodilab\Support\Bounds;
use Koodilab\Support\Util;

class Generator implements GeneratorContract
{
    /**
     * The size.
     *
     * @var int
     */
    const SIZE = 131072;

    /**
     * The scale.
     *
     * @var int
     */
    const SCALE = 65472;

    /**
     * The object count.
     *
     * @var int
     */
    const OBJECT_COUNT = 24576;

    /**
     * The minimum distance.
     *
     * @var int
     */
    const MIN_DISTANCE = 240;

    /**
     * The maximum distance.
     *
     * @var int
     */
    const MAX_DISTANCE = 288;

    /**
     * The arm count.
     *
     * @var int
     */
    const ARM_COUNT = 5;

    /**
     * The arm offset.
     *
     * @var float
     */
    const ARM_OFFSET = 0.8;

    /**
     * The coordinate offset.
     *
     * @var float
     */
    const COORDINATE_OFFSET = 0.02;

    /**
     * The speed.
     *
     * @var int
     */
    const SPEED = 5;

    /**
     * The grid count.
     *
     * @var int
     */
    const GRID_COUNT = 5;

    /**
     * The config repository implementation.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The database manager instance.
     *
     * @var DatabaseManager
     */
    protected $database;

    /**
     * The name generator implementation.
     *
     * @var NameGenerator
     */
    protected $nameGenerator;

    /**
     * The resources.
     *
     * @var \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Resource[]
     */
    protected $resources;

    /**
     * The validated.
     *
     * @var Bounds[]
     */
    protected $validated;

    /**
     * The x coordinate.
     *
     * @var int
     */
    protected $x;

    /**
     * The y coordinate.
     *
     * @var int
     */
    protected $y;

    /**
     * Constructor.
     *
     * @param Repository      $config
     * @param DatabaseManager $database
     * @param NameGenerator   $nameGenerator
     */
    public function __construct(Repository $config, DatabaseManager $database, NameGenerator $nameGenerator)
    {
        $this->config = $config;
        $this->database = $database;
        $this->nameGenerator = $nameGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $this->validated = [];
        $this->resources = Resource::all(['id', 'frequency']);

        if (count($this->resources)) {
            $this->database->transaction(function () {
                $this->generateStarmap();
            });
        }
    }

    /**
     * Generate the starmap.
     */
    protected function generateStarmap()
    {
        $objects = static::OBJECT_COUNT * $this->config->get('starmap.density');
        $ratio = $this->config->get('starmap.ratio');

        $armDistance = 2 * pi() / static::ARM_COUNT;
        $center = static::SIZE / 2;

        $i = 0;

        while ($i < $objects) {
            $distance = pow(Util::randFloat(), 2);

            $armOffset = $this->randArmOffest() * pow($distance, -1);
            $squaredArmOffset = pow($armOffset, 2);

            if ($armOffset < 0) {
                $squaredArmOffset = $squaredArmOffset * -1;
            }

            $armOffset = $squaredArmOffset;
            $rotation = $distance * static::SPEED;

            $angle = (int) (Util::randFloat() * 2 * pi() / $armDistance) * $armDistance + $armOffset + $rotation;

            $this->x = (int) ((cos($angle) * $distance + $this->randCoordinateOffset()) * static::SCALE + $center);
            $this->y = (int) ((sin($angle) * $distance + $this->randCoordinateOffset()) * static::SCALE + $center);

            if (! $this->validate()) {
                continue;
            }

            if (Util::randFloat() < $ratio) {
                $this->generateStar();
            } else {
                $this->generatePlanet();
            }

            ++$i;
        }
    }

    /**
     * Generate a star.
     */
    protected function generateStar()
    {
        Star::create([
            'x' => $this->x,
            'y' => $this->y,
            'name' => $this->nameGenerator->generate(),
        ]);
    }

    /**
     * Generate a planet.
     */
    protected function generatePlanet()
    {
        $planet = new Planet([
            'x' => $this->x,
            'y' => $this->y,
            'name' => $this->nameGenerator->generate(),
            'size' => mt_rand(Planet::SIZE_SMALL, Planet::SIZE_LARGE),
        ]);

        $planet->resource()->associate($this->randResource());
        $planet->save();

        $max = pow(static::GRID_COUNT, 2);
        $grids = range(1, $max);

        $center = (int) floor($max / 2);
        unset($grids[$center]);

        $resources = array_rand($grids, $planet->resource_count);
        $i = 0;

        for ($x = 0; $x < static::GRID_COUNT; ++$x) {
            for ($y = 0; $y < static::GRID_COUNT; ++$y) {
                $grid = new Grid([
                    'x' => $x,
                    'y' => $y,
                    'type' => Grid::TYPE_PLAIN,
                ]);

                if ($i == $center) {
                    $grid->type = Grid::TYPE_CENTRAL;
                } elseif (in_array($i, $resources)) {
                    $grid->type = Grid::TYPE_RESOURCE;
                }

                $grid->planet()->associate($planet);
                $grid->save();

                ++$i;
            }
        }
    }

    /**
     * Validate.
     *
     * @return bool
     */
    protected function validate()
    {
        foreach ($this->validated as $bounds) {
            if ($bounds->has($this->x, $this->y)) {
                return false;
            }
        }

        $distance = $this->randDistance();

        $this->validated[] = new Bounds(
            $this->x - $distance,
            $this->y - $distance,
            $this->x + $distance,
            $this->y + $distance
        );

        return true;
    }

    /**
     * Get a random resource.
     *
     * @return int
     */
    protected function randResource()
    {
        $resource = null;
        $randFrequency = $this->resources->sum('frequency') * Util::randFloat();

        foreach ($this->resources as $resource) {
            $randFrequency -= $resource->frequency;

            if ($randFrequency < 0) {
                return $resource;
            }
        }

        return $resource;
    }

    /**
     * Get a random distance.
     *
     * @return int
     */
    protected function randDistance()
    {
        return mt_rand(static::MIN_DISTANCE, static::MAX_DISTANCE);
    }

    /**
     * Get a random coordinate offset.
     *
     * @return float
     */
    protected function randCoordinateOffset()
    {
        return Util::randFloat() * static::COORDINATE_OFFSET;
    }

    /**
     * Get a random arm offset.
     *
     * @return float
     */
    protected function randArmOffest()
    {
        return Util::randFloat() * static::ARM_OFFSET - static::ARM_OFFSET / 2;
    }
}
