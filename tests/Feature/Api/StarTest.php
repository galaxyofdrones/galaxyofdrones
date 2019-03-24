<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Bookmark;
use Koodilab\Models\Expedition;
use Koodilab\Models\Star;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StarTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = factory(User::class)->create([
            'started_at' => Carbon::now(),
        ]);

        Passport::actingAs($user);
    }

    public function testShow()
    {
        $user = auth()->user();

        $star = factory(Star::class)->create();

        factory(Bookmark::class)->create([
            'user_id' => $user->id,
            'star_id' => $star->id,
        ]);

        factory(Expedition::class)->create([
            'user_id' => $user->id,
            'star_id' => $star->id,
            'ended_at' => Carbon::now()->addHour(),
        ]);

        $this->get('/api/star/10')
            ->assertStatus(404);

        $this->get('/api/star/not-id')
            ->assertStatus(404);

        $this->getJson("/api/star/{$star->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'is_bookmarked',
                'has_expedition',
            ])->assertJson([
                'id' => $star->id,
                'is_bookmarked' => true,
                'has_expedition' => true,
            ]);
    }
}
