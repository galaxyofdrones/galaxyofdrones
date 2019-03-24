<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
use Koodilab\Models\Stock;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProducerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 1,
            'y' => 1,
        ]);

        $user->update([
            'current_id' => $planet->id,
            'started_at' => Carbon::now(),
            'energy' => 10,
        ]);
    }

    public function testIndex()
    {
        $user = auth()->user();

        $building = factory(Building::class)->create([
            'type' => Building::TYPE_PRODUCER,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 2,
            'y' => 2,
        ]);

        $grid = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building->id,
        ]);

        $resource = factory(Resource::class)->create();

        $user->resources()->attach($resource, [
            'is_researched' => true,
            'quantity' => 10,
        ]);

        $this->getJson("/api/producer/{$grid->id}")->assertStatus(200)
            ->assertJsonStructure([
                'resources' => [
                    [
                        'id',
                        'name',
                        'frequency',
                        'efficiency',
                        'description',
                        'research_experience',
                        'research_cost',
                        'research_time',
                    ],
                ],
            ])->assertJson([
                'resources' => [
                    [
                        'id' => $resource->id,
                        'name' => $resource->translation('name'),
                        'frequency' => $resource->frequency,
                        'efficiency' => $resource->efficiency,
                        'description' => $resource->translation('description'),
                        'research_experience' => $resource->research_experience,
                        'research_cost' => $resource->research_cost,
                        'research_time' => $resource->research_time,
                    ],
                ],
            ]);
    }

    public function testStore()
    {
        $user = auth()->user();

        $building = factory(Building::class)->create([
            'type' => Building::TYPE_PRODUCER,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 3,
            'y' => 3,
        ]);

        $resource = factory(Resource::class)->create();

        factory(Stock::class)->create([
            'planet_id' => $user->current_id,
            'resource_id' => $resource->id,
            'quantity' => 20,
        ]);

        $grid = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building->id,
        ]);

        $this->post('/api/producer/10/10')
            ->assertStatus(404);

        $this->post('/api/producer/not-id/not-id')
            ->assertStatus(404);

        $this->post("/api/producer/{$grid->id}/{$resource->id}", [
            'quantity' => 10,
        ])->assertStatus(400);

        $user->resources()->attach($resource, [
            'is_researched' => true,
            'quantity' => 10,
        ]);

        $this->post("/api/producer/{$grid->id}/{$resource->id}", [
            'quantity' => 10,
        ])->assertStatus(200);

        $this->assertEquals($user->energy, 10 + round(10 * $resource->efficiency));
        $this->assertEquals($user->current->stocks()->first()->quantity, 10);
    }
}
