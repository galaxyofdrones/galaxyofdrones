<?php

namespace Koodilab\Tests\Unit\Support;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Setting;
use Koodilab\Support\SettingManager;
use Koodilab\Tests\TestCase;

class SettingManagerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var SettingManager
     */
    protected $settingManager;

    protected function setUp()
    {
        parent::setUp();

        $this->settingManager = app(SettingManager::class);
    }

    public function testAll()
    {
        factory(Setting::class, 2)->create();

        $this->assertCount(2, $this->settingManager->all());
        $this->assertTrue(cache()->has(SettingManager::CACHE_KEY));
    }

    public function testValue()
    {
        factory(Setting::class)->create([
            'key' => 'title',
            'value' => [
                'en' => 'TestTitle',
            ],
        ]);

        factory(Setting::class)->create([
            'key' => 'color',
            'value' => [
                'en' => 'Color',
                'en_GB' => 'Colour',
            ],
        ]);

        $this->assertEquals('TestTitle', $this->settingManager->value('title'));
        $this->assertEquals('TestTitle', $this->settingManager->value('title', 'en_GB'));
        $this->assertNull($this->settingManager->value('title', 'en_GB', false));
        $this->assertEquals('Colour', $this->settingManager->value('color', 'en_GB'));
    }

    public function testForget()
    {
        factory(Setting::class, 2)->create();

        $this->assertCount(2, $this->settingManager->all());
        $this->assertTrue(cache()->has(SettingManager::CACHE_KEY));

        $this->settingManager->forget();

        $this->assertFalse(cache()->has(SettingManager::CACHE_KEY));
    }
}
