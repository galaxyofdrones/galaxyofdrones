<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Planet;
use Koodilab\Models\Shield;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ShieldTest extends TestCase
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

    public function testIndex()
    {
        $user = auth()->user();

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 1,
            'y' => 1,
        ]);

        $shield = factory(Shield::class)->create([
            'planet_id' => $planet->id,
            'ended_at' => Carbon::now()->addDay(1),
        ]);

        $user->update([
            'solarion' => 2,
        ]);

        $this->getJson('/api/shield')->assertStatus(200)
            ->assertJsonStructure([
                'can_store',
                'shields' => [
                    [
                        'id',
                        'remaining',
                        'planet' => [
                            'id',
                            'resource_id',
                            'name',
                            'x',
                            'y',
                        ],
                    ],
                ],
            ])->assertJson([
                'can_store' => true,
                'shields' => [
                    [
                        'id' => $shield->id,
                        'remaining' => $shield->remaining,
                        'planet' => [
                            'id' => $planet->id,
                            'resource_id' => $planet->resource_id,
                            'name' => $planet->name,
                            'x' => $planet->x,
                            'y' => $planet->y,
                        ],
                    ],
                ],
            ]);
    }

    public function testStore()
    {
        $user = auth()->user();

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 2,
            'y' => 2,
        ]);

        $user->update([
            'solarion' => 0,
        ]);

        $this->post('/api/shield/10')
            ->assertStatus(404);

        $this->post('/api/shield/not-id')
            ->assertStatus(404);

        $this->post("/api/shield/{$planet->id}")
            ->assertStatus(400);

        $user->update([
            'solarion' => 2,
        ]);

        $this->post("/api/shield/{$planet->id}")
            ->assertStatus(200);

        $this->assertEquals($planet->user->solarion, 1);

        $this->assertDatabaseHas('shields', [
            'planet_id' => $planet->id,
        ]);
    }
}
