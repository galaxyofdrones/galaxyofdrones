<?php

namespace Koodilab\Support;

class Bounds
{
    /**
     * The minimum x coordinate.
     *
     * @var int
     */
    protected $minX;

    /**
     * The minimum y coordinate.
     *
     * @var int
     */
    protected $minY;

    /**
     * The maximum x coordinate.
     *
     * @var int
     */
    protected $maxX;

    /**
     * The maximum y coordinate.
     *
     * @var int
     */
    protected $maxY;

    /**
     * Constructor.
     *
     * @param int $minX
     * @param int $minY
     * @param int $maxX
     * @param int $maxY
     */
    public function __construct($minX = 0, $minY = 0, $maxX = 0, $maxY = 0)
    {
        $this->minX = $minX;
        $this->minY = $minY;
        $this->maxX = $maxX;
        $this->maxY = $maxY;
    }

    /**
     * From string.
     *
     * @param string $bounds
     *
     * @return static
     */
    public static function fromString($bounds)
    {
        list($minX, $minY, $maxX, $maxY) = explode(',', $bounds, 4);

        return new self($minX, $minY, $maxX, $maxY);
    }

    /**
     * Get the minimum x coordinate.
     *
     * @return int
     */
    public function minX()
    {
        return $this->minX;
    }

    /**
     * Set the minimum x coordinate.
     *
     * @param int $minX
     *
     * @return static
     */
    public function setMinX($minX)
    {
        $this->minX = $minX;

        return $this;
    }

    /**
     * Get the minimum y coordinate.
     *
     * @return int
     */
    public function minY()
    {
        return $this->minY;
    }

    /**
     * Set the minimum y coordinate.
     *
     * @param int $minY
     *
     * @return static
     */
    public function setMinY($minY)
    {
        $this->minY = $minY;

        return $this;
    }

    /**
     * Set the minimum x and y coordinate.
     *
     * @param int $minXY
     *
     * @return static
     */
    public function setMinXY($minXY)
    {
        $this->minX = $this->minY = $minXY;

        return $this;
    }

    /**
     * Get the maximum x coordinate.
     *
     * @return int
     */
    public function maxX()
    {
        return $this->maxX;
    }

    /**
     * Set the maximum x coordinate.
     *
     * @param int $maxX
     *
     * @return static
     */
    public function setMaxX($maxX)
    {
        $this->maxX = $maxX;

        return $this;
    }

    /**
     * Get the maximum y coordinate.
     *
     * @return int
     */
    public function maxY()
    {
        return $this->maxY;
    }

    /**
     * Set the maximum y coordinate.
     *
     * @param int $maxY
     *
     * @return static
     */
    public function setMaxY($maxY)
    {
        $this->maxY = $maxY;

        return $this;
    }

    /**
     * Set the maximum x and y coordinate.
     *
     * @param int $maxXY
     *
     * @return static
     */
    public function setMaxXY($maxXY)
    {
        $this->maxX = $this->maxY = $maxXY;

        return $this;
    }

    /**
     * Has the coordinate?
     *
     * @param int $x
     * @param int $y
     *
     * @return bool
     */
    public function has($x, $y)
    {
        return $this->minX <= $x &&
            $this->maxX >= $x &&
            $this->minY <= $y &&
            $this->maxY >= $y;
    }

    /**
     * Scale.
     *
     * @param float $value
     *
     * @return static
     */
    public function scale($value)
    {
        $a = $this->maxX - $this->minX;
        $b = $this->maxY - $this->minY;

        $centerX = $this->minX + $a / 2;
        $centerY = $this->minY + $b / 2;

        $offsetA = $a * $value / 2;
        $offsetB = $b * $value / 2;

        $this->minX = $centerX - $offsetA;
        $this->minY = $centerY - $offsetB;
        $this->maxX = $centerX + $offsetA;
        $this->maxY = $centerY + $offsetB;

        return $this;
    }
}
