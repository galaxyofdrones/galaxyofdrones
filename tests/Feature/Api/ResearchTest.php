<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Bookmark;
use Koodilab\Models\Research;
use Koodilab\Models\Resource;
use Koodilab\Models\Star;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ResearchTest extends TestCase
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

    public function testIndex()
    {
        $user = auth()->user();

        $resource1 = factory(Resource::class)->create();
        $resource2 = factory(Resource::class)->create();

        $unit1 = factory(Unit::class)->create();
        $unit2 = factory(Unit::class)->create();

        $resourceResearch = factory(Research::class)->create([
            'user_id' => $user->id,
        ]);

        $resourceResearch->researchable()->associate($resource2);

        $unitResearch = factory(Research::class)->create([
            'user_id' => $user->id,
        ]);

        $unitResearch->researchable()->associate($unit2);

        $user->resources()->attach($resource1, [
            'is_researched' => true,
            'quantity' => 1,
        ]);

        $user->units()->attach($unit1, [
            'is_researched' => true,
            'quantity' => 1,
        ]);

        $this->getJson('/api/research')->assertStatus(200)
            ->assertJsonStructure([
                'resource' => [
                    'id',
                    'name',
                    'frequency',
                    'efficiency',
                    'description',
                    'research_experience',
                    'research_cost',
                    'research_time',
                    'remaining',
                ],
                'units' => [
                    [
                        'id',
                        'name',
                        'type',
                        'speed',
                        'attack',
                        'defense',
                        'supply',
                        'train_cost',
                        'train_time',
                        'description',
                        'detection',
                        'capacity',
                        'research_experience',
                        'research_cost',
                        'research_time',
                        'remaining',
                    ]
                ],
            ])->assertJson([
                'resource' => [
                    'id' => $resource2->id,
                    'name' => $resource2->translation('name'),
                    'frequency' => $resource2->frequency,
                    'efficiency' => $resource2->efficiency,
                    'description' => $resource2->translation('description'),
                    'research_experience' => $resource2->research_experience,
                    'research_cost' => $resource2->research_cost,
                    'research_time' => $resource2->research_time,
                    'remaining' => $resourceResearch->remaining,
                ],
                'units' => [
                    [
                        'id' => $unit2->id,
                        'name' => $unit2->translation('name'),
                        'type' => $unit2->type,
                        'speed' => $unit2->speed,
                        'attack' => $unit2->attack,
                        'defense' => $unit2->defense,
                        'supply' => $unit2->supply,
                        'train_cost' => $unit2->train_cost,
                        'train_time' => $unit2->train_time,
                        'description' => $unit2->translation('description'),
                        'detection' => $unit2->detection,
                        'capacity' => $unit2->capacity,
                        'research_experience' => $unit2->research_experience,
                        'research_cost' => $unit2->research_cost,
                        'research_time' => $unit2->research_time,
                        'remaining' => $unitResearch->remaining,
                    ],
                ],
            ]);
    }
}
