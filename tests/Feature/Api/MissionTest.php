<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Mission;
use Koodilab\Models\Resource;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MissionTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = factory(User::class)->create([
            'started_at' => Carbon::now(),
            'solarion' => 50,
        ]);

        Passport::actingAs($user);
    }

    public function testIndex()
    {
        $user = auth()->user();

        $mission = factory(Mission::class)->create([
            'user_id' => $user->id,
            'ended_at' => Carbon::now()->addHour(),
        ]);

        $resource = factory(Resource::class)->create();

        $mission->resources()->attach($resource->id, [
            'quantity' => 10,
        ]);

        $user->resources()->attach($resource->id, [
            'is_researched' => true,
            'quantity' => 8,
        ]);

        $this->getJson('/api/mission')->assertStatus(200)
            ->assertJsonStructure([
                'solarion',
                'resources' => [
                    [
                        'id',
                        'name',
                        'description',
                        'quantity',
                    ],
                ],
                'missions' => [
                    [
                        'id',
                        'energy',
                        'experience',
                        'remaining',
                        'resources' => [
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
                'solarion' => $user->solarion,
                'resources' => [
                    [
                        'id' => $resource->id,
                        'name' => $resource->name['en'],
                        'description' => $resource->description['en'],
                        'quantity' => 8,
                    ],
                ],
                'missions' => [
                    [
                        'id' => $mission->id,
                        'energy' => $mission->energy,
                        'experience' => $mission->experience,
                        'remaining' => $mission->remaining,
                        'resources' => [
                            [
                                'id' => $resource->id,
                                'name' => $resource->name['en'],
                                'description' => $resource->description['en'],
                                'quantity' => 10,
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function testStore()
    {
        $user = auth()->user();

        $this->post('/api/mission/10')
            ->assertStatus(404);

        $this->post('/api/mission/not-id')
            ->assertStatus(404);

        $mission = factory(Mission::class)->create([
            'user_id' => auth()->user()->id,
        ]);

        $resource = factory(Resource::class)->create();

        $mission->resources()->attach($resource->id, [
            'quantity' => 10,
        ]);

        $user->resources()->attach($resource->id, [
            'is_researched' => false,
            'quantity' => 8,
        ]);

        $this->post("/api/mission/{$mission->id}")
            ->assertStatus(400);

        $user->resources()->updateExistingPivot($user->resources()->first()->id, [
            'quantity' => 18,
        ]);

        $this->assertDatabaseHas('missions', [
            'id' => $mission->id,
        ]);

        $this->post("/api/mission/{$mission->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('missions', [
            'id' => $mission->id,
        ]);
    }
}
