<?php

namespace Koodilab\Starmap;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Imagick;
use ImagickPixel;
use Koodilab\Contracts\Starmap\Renderer as RendererContract;
use Koodilab\Models\Planet;
use Koodilab\Models\Star;
use Koodilab\Support\Bounds;

class Renderer implements RendererContract
{
    /**
     * The tile size.
     *
     * @var int
     */
    const TILE_SIZE = 256;

    /**
     * The tile safe size.
     *
     * @var int
     */
    const TILE_SAFE_SIZE = 352;

    /**
     * The maximum zoom level.
     *
     * @var int
     */
    const MAX_ZOOM_LEVEL = 9;

    /**
     * The star zoom level.
     *
     * @var int
     */
    const STAR_ZOOM_LEVEL = 0;

    /**
     * The planet zoom level.
     *
     * @var int
     */
    const PLANET_ZOOM_LEVEL = 7;

    /**
     * The config repository implementation.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The textures.
     *
     * @var Imagick[]
     */
    protected $textures;

    /**
     * The tile instance.
     *
     * @var Imagick
     */
    protected $tile;

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
     * The z coordinate.
     *
     * @var int
     */
    protected $z;

    /**
     * The ratio.
     *
     * @var float
     */
    protected $ratio;

    /**
     * The bounds instance.
     *
     * @var Bounds
     */
    protected $bounds;

    /**
     * Constructor.
     *
     * @param Repository $config
     * @param Filesystem $filesystem
     */
    public function __construct(Repository $config, Filesystem $filesystem)
    {
        $this->config = $config;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->textures = [];
        $this->renderTiles();
    }

    /**
     * Render the tiles.
     */
    protected function renderTiles()
    {
        $tileRadius = static::TILE_SIZE / 2;
        $tileSafeRadius = static::TILE_SAFE_SIZE / 2;
        $maxTileCount = pow(2, static::MAX_ZOOM_LEVEL);

        for ($z = 0; $z <= static::MAX_ZOOM_LEVEL; ++$z) {
            $this->z = $z;
            $tileCount = pow(2, $this->z);
            $this->ratio = $tileCount / $maxTileCount;

            for ($x = 0; $x < $tileCount; ++$x) {
                $this->x = $x;
                $this->filesystem->makeDirectory($this->tileDir(), 0755, true, true);

                for ($y = 0; $y < $tileCount; ++$y) {
                    $this->y = $y;

                    $centerX = $this->x * static::TILE_SIZE + $tileRadius;
                    $centerY = $this->y * static::TILE_SIZE + $tileRadius;

                    $this->bounds = new Bounds(
                        ($centerX - $tileSafeRadius) / $this->ratio,
                        ($centerY - $tileSafeRadius) / $this->ratio,
                        ($centerX + $tileSafeRadius) / $this->ratio,
                        ($centerY + $tileSafeRadius) / $this->ratio
                    );

                    $this->renderTile();
                }
            }
        }
    }

    /**
     * Render a tile.
     */
    protected function renderTile()
    {
        $this->makeTile();

        if ($this->z >= static::STAR_ZOOM_LEVEL) {
            $stars = Star::inBounds($this->bounds)->get();

            $this->compositeObjects($stars, function ($textureDir) {
                return "{$textureDir}/star/{$this->z}.png";
            });
        }

        if ($this->z >= static::PLANET_ZOOM_LEVEL) {
            $planets = Planet::inBounds($this->bounds)->get();

            $this->compositeObjects($planets, function ($textureDir, Planet $planet) {
                return "{$textureDir}/planet/{$planet->resource_id}/{$this->z}/{$planet->size}.png";
            });
        }

        $this->saveTile();
    }

    /**
     * Make a tile instance.
     */
    protected function makeTile()
    {
        $this->tile = new Imagick();
        $this->tile->newImage(static::TILE_SAFE_SIZE, static::TILE_SAFE_SIZE, new ImagickPixel('transparent'), 'png');
    }

    /**
     * Composite the objects.
     *
     * @param mixed   $objects
     * @param Closure $textureFilename
     */
    protected function compositeObjects($objects, Closure $textureFilename)
    {
        $textureDir = $this->config->get('starmap.texture_dir');

        foreach ($objects as $object) {
            $texture = $this->texture($textureFilename($textureDir, $object));

            $x = ($object->x - $this->bounds->minX()) * $this->ratio - $texture->getImageWidth() / 2;
            $y = ($object->y - $this->bounds->minY()) * $this->ratio - $texture->getImageHeight() / 2;

            $this->tile->compositeImage($texture, Imagick::COMPOSITE_DEFAULT, $x, $y);
        }
    }

    /**
     * Save the tile.
     */
    protected function saveTile()
    {
        $offset = (static::TILE_SAFE_SIZE - static::TILE_SIZE) / 2;

        $this->tile->cropImage(static::TILE_SIZE, static::TILE_SIZE, $offset, $offset);
        $this->tile->writeImage($this->tileFilename());
    }

    /**
     * Get the tile filename.
     *
     * @return string
     */
    protected function tileFilename()
    {
        return "{$this->tileDir()}/{$this->y}.png";
    }

    /**
     * Get the tile directory.
     *
     * @return string
     */
    protected function tileDir()
    {
        return "{$this->config->get('starmap.absolute_dir')}/{$this->z}/{$this->x}";
    }

    /**
     * Get the texture.
     *
     * @param string $filename
     *
     * @return Imagick
     */
    protected function texture($filename)
    {
        if (! array_key_exists($filename, $this->textures)) {
            $this->textures[$filename] = $this->filesystem->exists($filename)
                ? new Imagick($filename)
                : null;
        }

        return $this->textures[$filename];
    }
}
