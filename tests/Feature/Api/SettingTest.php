<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Setting;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        factory(Setting::class)->create([
            'key' => 'title',
            'value' => [
                'en' => 'TestTitle',
            ],
        ]);

        factory(Setting::class)->create([
            'key' => 'description',
            'value' => [
                'en' => 'Test Description.',
            ],
        ]);

        factory(Setting::class)->create([
            'key' => 'author',
            'value' => [
                'en' => 'TestAuthor',
            ],
        ]);

        $user = factory(User::class)->create([
            'email' => 'support+developer@koodilab.com',
            'started_at' => Carbon::now(),
        ]);

        Passport::actingAs($user);
    }

    public function testUpdate()
    {
        $this->put('/api/setting')->assertStatus(403);

        config()->set('debug.emails', ['support+developer@koodilab.com']);

        $this->putJson('/api/setting')
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors',
                'message',
            ]);

        $this->assertEquals('TestTitle', setting('title'));
        $this->assertEquals('Test Description.', setting('description'));
        $this->assertEquals('TestAuthor', setting('author'));

        $this->putJson('/api/setting', [
            'title' => 'NewTitle',
            'description' => 'New Description.',
            'author' => 'NewAuthor',
        ])->assertStatus(200);

        $this->assertEquals('NewTitle', setting('title'));
        $this->assertEquals('New Description.', setting('description'));
        $this->assertEquals('NewAuthor', setting('author'));
    }
}
