<?php

namespace Tests\Unit\Starmap;

use App\Models\Planet;
use App\Models\Resource;
use App\Models\Star;
use App\Starmap\Generator;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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

    /**
     * @throws \Exception|\Throwable
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->config = $this->app->make('config');

        $this->generator = $this->app->make(Generator::class);
    }

    /**
     * @throws \Exception|\Throwable
     */
    public function testGenerate()
    {
        Resource::factory()->create();

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
