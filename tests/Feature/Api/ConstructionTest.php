<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Koodilab\Events\UserUpdated;
use Koodilab\Models\Building;
use Koodilab\Models\Construction;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ConstructionTest extends TestCase
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

        $initialDispatcher = Event::getFacadeRoot();

        Event::fake();

        Model::setEventDispatcher($initialDispatcher);

        $user->update([
            'capital_id' => $planet->id,
            'current_id' => $planet->id,
            'started_at' => Carbon::now(),
        ]);

        Event::assertDispatched(UserUpdated::class, function ($event) use ($user) {
            return $event->userId === $user->id;
        });

        $user->resources()->attach($planet->resource_id, [
            'is_researched' => true,
            'quantity' => 0,
        ]);
    }

    public function testIndex()
    {
        $building = factory(Building::class)->create([
            'type' => Building::TYPE_DEFENSIVE,
            'end_level' => 10,
            'construction_experience' => 50,
            'construction_cost' => 100,
            'construction_time' => 25,
        ]);

        $grid = factory(Grid::class)->create([
            'building_id' => null,
            'planet_id' => auth()->user()->current->id,
        ]);

        factory(Construction::class)->create([
            'building_id' => $building->id,
            'grid_id' => $grid->id,
        ]);

        $building = $grid->constructionBuildings()->first();

        $this->getJson("/api/construction/{$grid->id}")->assertStatus(200)
            ->assertJsonStructure([
                'remaining',
                'buildings' => [
                    [
                        'id',
                        'name',
                        'name_with_level',
                        'type',
                        'construction_experience',
                        'construction_cost',
                        'construction_time',
                        'description',
                        'defense',
                        'detection',
                        'capacity',
                        'supply',
                        'mining_rate',
                        'production_rate',
                        'defense_bonus',
                        'construction_time_bonus',
                        'trade_time_bonus',
                        'train_time_bonus',
                        'has_lower_level',
                    ],
                ],
            ])->assertJson([
                'remaining' => $grid->construction->remaining,
                'buildings' => [
                    [
                        'id' => $building->id,
                        'name' => $building->name['en'],
                        'name_with_level' => "{$building->name['en']} (Level {$building->level})",
                        'type' => $building->type,
                        'construction_experience' => $building->construction_experience,
                        'construction_cost' => $building->construction_cost,
                        'construction_time' => $building->construction_time,
                        'description' => $building->description['en'],
                        'defense' => $building->defense,
                        'detection' => $building->detection,
                        'capacity' => $building->capacity,
                        'supply' => $building->supply,
                        'mining_rate' => $building->mining_rate,
                        'production_rate' => $building->production_rate,
                        'defense_bonus' => $building->defense_bonus,
                        'construction_time_bonus' => $building->construction_time_bonus,
                        'trade_time_bonus' => $building->trade_time_bonus,
                        'train_time_bonus' => $building->train_time_bonus,
                        'has_lower_level' => $building->hasLowerLevel(),
                    ],
                ],
            ]);
    }

    public function testStore()
    {
        $user = auth()->user();

        $building = factory(Building::class)->create([
            'construction_cost' => 100,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => auth()->user()->id,
            'x' => 800,
            'y' => 800,
        ]);

        $grid = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'x' => 5,
            'y' => 7,
        ]);

        factory(Construction::class)->create([
            'grid_id' => $grid->id,
        ]);

        $this->post('/api/construction/10/10')
            ->assertStatus(404);

        $this->post('/api/construction/not-id')
            ->assertStatus(404);

        $this->post("/api/construction/{$grid->id}/{$building->id}")
            ->assertStatus(400);

        $building2 = factory(Building::class)->create([
            'construction_cost' => 10000,
            'parent_id' => $building->id,
            'type' => Building::TYPE_MINER,
            'limit' => 0,
        ]);

        factory(Grid::class)->create([
            'building_id' => $building->id,
            'planet_id' => $planet->id,
            'x' => 10,
            'y' => 8,
        ]);

        $grid2 = factory(Grid::class)->create([
            'building_id' => null,
            'planet_id' => $planet->id,
            'type' => Grid::TYPE_RESOURCE,
            'x' => 3,
            'y' => 9,
        ]);

        for ($i = 1; $i < 10; ++$i) {
            $tmpPlanet = factory(Planet::class)->create([
                'user_id' => null,
                'x' => $user->capital->x + Planet::PENALTY_STEP + $i,
                'y' => $user->capital->y + Planet::PENALTY_STEP + $i,
            ]);

            $tmpPlanet->update([
                'user_id' => $user->id,
            ]);
        }

        $user->update([
            'energy' => 188,
        ]);

        $this->post("/api/construction/{$grid2->id}/{$building2->id}")
            ->assertStatus(400);

        $user->update([
            'energy' => 189,
        ]);

        $this->post("/api/construction/{$grid2->id}/{$building2->id}")
            ->assertStatus(200);
    }

    public function testDestroy()
    {
        $grid = factory(Grid::class)->create([
            'planet_id' => auth()->user()->current->id,
        ]);

        $this->delete('/api/construction/10')
            ->assertStatus(404);

        $this->delete('/api/construction/not-id')
            ->assertStatus(404);

        $this->delete("/api/construction/{$grid->id}")
            ->assertStatus(400);

        $construction = factory(Construction::class)->create([
            'grid_id' => $grid->id,
        ]);

        $this->assertDatabaseHas('constructions', [
            'id' => $construction->id,
        ]);

        $this->delete("/api/construction/{$grid->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('constructions', [
            'id' => $construction->id,
        ]);
    }
}
