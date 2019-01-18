<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Star;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StarTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $user = factory(User::class)->create([
            'started_at' => Carbon::now(),
        ]);

        Passport::actingAs($user);
    }

    public function testShow()
    {
        $star = factory(Star::class)->create([
            'name' => 'Voyager',
            'x' => 15,
            'y' => 27,
        ]);

        $this->get('/api/star/10')
            ->assertStatus(404);

        $this->get('/api/star/not-id')
            ->assertStatus(404);

        $this->getJson("/api/star/{$star->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'isBookmarked',
                'hasExpedition',
            ])->assertJson([
                'id' => 1,
                'isBookmarked' => false,
                'hasExpedition' => false,
            ]);
    }
}
