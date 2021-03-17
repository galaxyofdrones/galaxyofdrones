<?php

namespace Tests\Feature\Api;

use App\Models\Bookmark;
use App\Models\Expedition;
use App\Models\Star;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StarTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'started_at' => Carbon::now(),
        ]);

        Passport::actingAs($user);
    }

    public function testShow()
    {
        $user = auth()->user();

        $star = Star::factory()->create();

        Bookmark::factory()->create([
            'user_id' => $user->id,
            'star_id' => $star->id,
        ]);

        Expedition::factory()->create([
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
