<?php

namespace Koodilab\Tests\Unit\Starmap;

use Koodilab\Starmap\NameGenerator;
use Koodilab\Tests\TestCase;

class NameGeneratorTest extends TestCase
{
    /**
     * @var NameGenerator
     */
    protected $nameGenerator;

    public function setUp()
    {
        parent::setUp();

        $this->nameGenerator = new NameGenerator();
    }

    public function testGenerate()
    {
        $this->assertInternalType('string', $this->nameGenerator->generate());
    }
}
