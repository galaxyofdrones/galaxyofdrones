<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Bus;
use Koodilab\Jobs\Move;
use Koodilab\Models\Grid;
use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\Population;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MovementTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'name' => 'Earth',
        ]);

        $user->update([
            'current_id' => $planet->id,
            'started_at' => Carbon::now(),
        ]);
    }

    public function testStoreScout()
    {
        $user = auth()->user();

        $this->post('/api/movement/scout/10')
            ->assertStatus(404);

        $this->post('/api/movement/scout/not-id')
            ->assertStatus(404);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->post("/api/movement/scout/{$planet->id}")
            ->assertStatus(403);

        $unit = factory(Unit::class)->create([
            'type' => Unit::TYPE_SCOUT,
            'speed' => 100,
        ]);

        $population = factory(Population::class)->create([
            'planet_id' => $user->current_id,
            'unit_id' => $unit->id,
            'quantity' => 5,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => null,
        ]);

        $this->post("/api/movement/scout/{$planet->id}", [
            'quantity' => 10,
        ])->assertStatus(400);

        $population->update([
            'quantity' => 20,
        ]);

        $this->assertDatabaseMissing('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_SCOUT,
        ]);

        Bus::fake();

        $this->post("/api/movement/scout/{$planet->id}", [
            'quantity' => 10,
        ])->assertStatus(200);

        Bus::assertDispatched(Move::class);

        $this->assertDatabaseHas('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_SCOUT,
        ]);
    }

    public function testStoreAttack()
    {
        $user = auth()->user();

        $this->post('/api/movement/attack/10')
            ->assertStatus(404);

        $this->post('/api/movement/attack/not-id')
            ->assertStatus(404);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->post("/api/movement/attack/{$planet->id}")
            ->assertStatus(403);

        $unit = factory(Unit::class)->create([
            'type' => Unit::TYPE_FIGHTER,
            'speed' => 100,
        ]);

        $population = factory(Population::class)->create([
            'planet_id' => $user->current_id,
            'unit_id' => $unit->id,
            'quantity' => 5,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => null,
        ]);

        $this->post("/api/movement/attack/{$planet->id}", [
            'quantity' => [
                $unit->id => 10,
            ],
        ])->assertStatus(400);

        $population->update([
            'quantity' => 20,
        ]);

        $this->assertDatabaseMissing('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_ATTACK,
        ]);

        Bus::fake();

        $this->post("/api/movement/attack/{$planet->id}", [
            'quantity' => [
                $unit->id => 10,
            ],
        ])->assertStatus(200);

        Bus::assertDispatched(Move::class);

        $this->assertDatabaseHas('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_ATTACK,
        ]);
    }

    public function testStoreOccupy()
    {
        $user = auth()->user();

        $this->post('/api/movement/occupy/10')
            ->assertStatus(404);

        $this->post('/api/movement/occupy/not-id')
            ->assertStatus(404);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->post("/api/movement/occupy/{$planet->id}")
            ->assertStatus(403);

        $resource = factory(Resource::class)->create();

        $planet = factory(Planet::class)->create([
            'user_id' => null,
            'resource_id' => $resource->id,
        ]);

        $this->post("/api/movement/occupy/{$planet->id}")->assertStatus(400);

        $user->resources()->attach($resource, [
            'is_researched' => true,
            'quantity' => 10,
        ]);

        $unit = factory(Unit::class)->create([
            'type' => Unit::TYPE_SETTLER,
            'speed' => 100,
        ]);

        $population = factory(Population::class)->create([
            'planet_id' => $user->current_id,
            'unit_id' => $unit->id,
        ]);

        $grid = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'type' => Grid::TYPE_CENTRAL,
        ]);

        $this->assertDatabaseMissing('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_OCCUPY,
        ]);

        Bus::fake();

        $this->post("/api/movement/occupy/{$planet->id}")->assertStatus(200);

        Bus::assertDispatched(Move::class);

        $this->assertDatabaseHas('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_OCCUPY,
        ]);
    }
}
