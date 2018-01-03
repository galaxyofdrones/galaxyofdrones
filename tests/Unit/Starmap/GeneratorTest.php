<?php

namespace Koodilab\Tests\Unit\Starmap;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
use Koodilab\Models\Star;
use Koodilab\Starmap\Generator;
use Koodilab\Tests\TestCase;

class GeneratorTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Generator
     */
    protected $generator;

    public function setUp()
    {
        parent::setUp();

        $this->config = $this->app->make('config');

        $this->generator = $this->app->make(Generator::class);
    }

    public function testGenerate()
    {
        factory(Resource::class)->create();

        $this->config->set('starmap.density', 0.001);
        $this->config->set('starmap.ratio', 0.2);

        $this->generator->generate();

        $starCount = Star::count();
        $planetCount = Planet::count();

        $this->assertTrue($starCount > 0);
        $this->assertTrue($planetCount > 0);
        $this->assertTrue($planetCount > $starCount);
    }
}
