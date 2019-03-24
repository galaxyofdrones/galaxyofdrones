<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Research;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ResearchTest extends TestCase
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

        $resource1 = factory(Resource::class)->create();
        $resource2 = factory(Resource::class)->create();

        $unit1 = factory(Unit::class)->create();
        $unit2 = factory(Unit::class)->create();

        $resourceResearch = factory(Research::class)->create([
            'user_id' => $user->id,
            'researchable_type' => Resource::class,
            'researchable_id' => $resource2->id,
        ]);

        $unitResearch = factory(Research::class)->create([
            'user_id' => $user->id,
            'researchable_type' => Unit::class,
            'researchable_id' => $unit2->id,
        ]);

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
                    ],
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

    public function testStoreResource()
    {
        $user = auth()->user();

        $this->post('/api/research/resource')
            ->assertStatus(400);

        $resource1 = factory(Resource::class)->create([
            'research_cost' => 50,
        ]);

        $resource2 = factory(Resource::class)->create();

        factory(Research::class)->create([
            'user_id' => $user->id,
            'researchable_type' => Resource::class,
            'researchable_id' => $resource2->id,
        ]);

        $user->resources()->attach($resource1, [
            'is_researched' => true,
            'quantity' => 1,
        ]);

        $this->post('/api/research/resource')
            ->assertStatus(400);

        $user->resources()->detach($resource1);

        $user->update([
            'energy' => 10,
        ]);

        $this->post('/api/research/resource')
            ->assertStatus(400);

        $user->update([
            'energy' => 100,
        ]);

        $this->post('/api/research/resource')
            ->assertStatus(200);
    }

    public function testStoreUnit()
    {
        $user = auth()->user();

        $this->post('/api/research/10')
            ->assertStatus(404);

        $this->post('/api/research/not-id')
            ->assertStatus(404);

        $unit1 = factory(Unit::class)->create([
            'research_cost' => 50,
        ]);

        $unit2 = factory(Unit::class)->create();

        factory(Research::class)->create([
            'user_id' => $user->id,
            'researchable_type' => Unit::class,
            'researchable_id' => $unit2->id,
        ]);

        $user->units()->attach($unit1, [
            'is_researched' => true,
            'quantity' => 1,
        ]);

        $this->post("/api/research/{$unit1->id}")
            ->assertStatus(400);

        $user->units()->detach($unit1);

        $user->update([
            'energy' => 10,
        ]);

        $this->post("/api/research/{$unit1->id}")
            ->assertStatus(400);

        $user->update([
            'energy' => 100,
        ]);

        $this->post("/api/research/{$unit1->id}")
            ->assertStatus(200);
    }

    public function testDestroyResource()
    {
        $user = auth()->user();

        $this->delete('/api/research/resource')
            ->assertStatus(400);

        $resource = factory(Resource::class)->create([
            'research_time' => 60,
            'research_cost' => 50,
        ]);

        factory(Research::class)->create([
            'user_id' => $user->id,
            'researchable_type' => Resource::class,
            'researchable_id' => $resource->id,
        ]);

        $this->delete('/api/research/resource')
            ->assertStatus(200);
    }

    public function testDestroyUnit()
    {
        $user = auth()->user();

        $this->delete('/api/research/10')
            ->assertStatus(404);

        $this->delete('/api/research/not-id')
            ->assertStatus(404);

        $unit = factory(Unit::class)->create([
            'research_time' => 60,
            'research_cost' => 50,
        ]);

        $this->delete("/api/research/{$unit->id}")
            ->assertStatus(400);

        factory(Research::class)->create([
            'user_id' => $user->id,
            'researchable_type' => Unit::class,
            'researchable_id' => $unit->id,
        ]);

        $this->delete("/api/research/{$unit->id}")
            ->assertStatus(200);
    }
}
