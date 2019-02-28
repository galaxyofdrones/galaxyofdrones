<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Expedition;
use Koodilab\Models\Star;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ExpeditionTest extends TestCase
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

        $star = factory(Star::class)->create([
            'name' => 'Voyager',
        ]);

        $unit = factory(Unit::class)->create([
            'name' => [
                'en' => 'Fighter',
            ],
            'description' => [
                'en' => 'english description',
            ],
        ]);

        $expedition = factory(Expedition::class)->create([
            'star_id' => $star->id,
            'user_id' => $user->id,
            'solarion' => 5,
            'experience' => 2,
            'ended_at' => Carbon::now()->addHour(),
        ]);

        $expedition->units()->attach($unit, [
            'quantity' => 5,
        ]);

        $user->units()->attach($unit, [
            'is_researched' => true,
            'quantity' => 4,
        ]);

        $this->getJson('/api/expedition')->assertStatus(200)
            ->assertJsonStructure([
                'units' => [
                    [
                        'id',
                        'name',
                        'description',
                        'quantity',
                    ],
                ],
                'expeditions' => [
                    [
                        'id',
                        'star',
                        'solarion',
                        'experience',
                        'remaining',
                        'units' => [
                            [
                                'id',
                                'name',
                                'description',
                                'quantity',
                            ],
                        ],
                    ],
                ],
            ])->assertJson([
                'units' => [
                    [
                        'id' => $unit->id,
                        'name' => $unit->translation('name'),
                        'description' => $unit->translation('description'),
                        'quantity' => 4,
                    ],
                ],
                'expeditions' => [
                    [
                        'id' => $expedition->id,
                        'star' => $star->name,
                        'solarion' => $expedition->solarion,
                        'experience' => $expedition->experience,
                        'remaining' => $expedition->remaining,
                        'units' => [
                            [
                                'id' => $unit->id,
                                'name' => $unit->translation('name'),
                                'description' => $unit->translation('description'),
                                'quantity' => 5,
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function testStore()
    {
        $user = auth()->user();
        $star = factory(Expedition::class)->create();

        $expedition = factory(Expedition::class)->create([
            'star_id' => $star->id,
            'user_id' => $user->id,
        ]);

        $this->post('/api/expedition/10')
            ->assertStatus(404);

        $this->post('/api/expedition/not-id')
            ->assertStatus(404);

        $this->assertDatabaseHas('expeditions', [
            'id' => $expedition->id,
        ]);

        $this->post("/api/expedition/{$expedition->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('expeditions', [
            'id' => $expedition->id,
        ]);

        $this->assertDatabaseHas('expedition_logs', [
            'star_id' => $star->id,
            'user_id' => $user->id,
        ]);
    }
}
