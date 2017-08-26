<?php

namespace Koodilab\Tests\Unit\Starmap;

use Koodilab\Starmap\NameGenerator;
use Koodilab\Tests\TestCase;

class NameGeneratorTest extends TestCase
{
    /**
     * The name generator instance.
     *
     * @var NameGenerator
     */
    protected $nameGenerator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->nameGenerator = new NameGenerator();
    }

    /**
     * generate() test.
     */
    public function testGenerate()
    {
        $this->assertInternalType('string', $this->nameGenerator->generate());
    }
}
