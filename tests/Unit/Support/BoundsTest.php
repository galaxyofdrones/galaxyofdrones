<?php

namespace Tests\Models;

use Koodilab\Support\Bounds;
use Tests\TestCase;

class BoundsTest extends TestCase
{
    public function testFromString()
    {
        $bounds = Bounds::fromString('0.5,1.0,1.5,2.0');

        $this->assertEquals(0.5, $bounds->minX());
        $this->assertEquals(1.0, $bounds->minY());
        $this->assertEquals(1.5, $bounds->maxX());
        $this->assertEquals(2.0, $bounds->maxY());
    }

    public function testHas()
    {
        $bounds = new Bounds(1.0, 1.0, 3.0, 3.0);

        $this->assertTrue($bounds->has(2.0, 2.5));
        $this->assertFalse($bounds->has(3.0, 3.5));
    }

    public function testScale()
    {
        $bounds = (new Bounds(1.0, 1.0, 3.0, 3.0))->scale(1.5);

        $this->assertEquals(0.5, $bounds->minX());
        $this->assertEquals(0.5, $bounds->minY());
        $this->assertEquals(3.5, $bounds->maxX());
        $this->assertEquals(3.5, $bounds->maxY());

        $bounds->scale(1 / 1.5);

        $this->assertEquals(1.0, $bounds->minX());
        $this->assertEquals(1.0, $bounds->minY());
        $this->assertEquals(3.0, $bounds->maxX());
        $this->assertEquals(3.0, $bounds->maxY());
    }
}
