<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Bus;
use Koodilab\Jobs\Move;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\Population;
use Koodilab\Models\Resource;
use Koodilab\Models\Stock;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MovementTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'name' => 'Earth',
            'x' => 1,
            'y' => 1,
        ]);

        $user->update([
            'capital_id' => $planet->id,
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
            'x' => 2,
            'y' => 2,
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
            'x' => 3,
            'y' => 3,
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
            'x' => 4,
            'y' => 4,
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
            'x' => 5,
            'y' => 5,
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
            'x' => 6,
            'y' => 6,
        ]);

        $this->post("/api/movement/occupy/{$planet->id}")
            ->assertStatus(403);

        $resource = factory(Resource::class)->create();

        $planet = factory(Planet::class)->create([
            'user_id' => null,
            'resource_id' => $resource->id,
            'x' => 7,
            'y' => 7,
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

        factory(Population::class)->create([
            'planet_id' => $user->current_id,
            'unit_id' => $unit->id,
        ]);

        factory(Grid::class)->create([
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

    public function testStoreSupport()
    {
        $user = auth()->user();

        $this->post('/api/movement/support/10')
            ->assertStatus(404);

        $this->post('/api/movement/support/not-id')
            ->assertStatus(404);

        $planet = factory(Planet::class)->create([
            'user_id' => null,
            'x' => 8,
            'y' => 8,
        ]);

        $this->post("/api/movement/support/{$planet->id}")
            ->assertStatus(403);

        $unit = factory(Unit::class)->create([
            'speed' => 100,
        ]);

        $population = factory(Population::class)->create([
            'planet_id' => $user->current_id,
            'unit_id' => $unit->id,
            'quantity' => 5,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 9,
            'y' => 9,
        ]);

        $this->post("/api/movement/support/{$planet->id}", [
            'quantity' => [
                $unit->id => 10,
            ],
        ])->assertStatus(400);

        $population->update([
            'quantity' => 20,
        ]);

        $this->assertDatabaseMissing('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_SUPPORT,
        ]);

        Bus::fake();

        $this->post("/api/movement/support/{$planet->id}", [
            'quantity' => [
                $unit->id => 10,
            ],
        ])->assertStatus(200);

        Bus::assertDispatched(Move::class);

        $this->assertDatabaseHas('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_SUPPORT,
        ]);
    }

    public function testStoreTransport()
    {
        $user = auth()->user();

        $this->post('/api/movement/transport/10')
            ->assertStatus(404);

        $this->post('/api/movement/transport/not-id')
            ->assertStatus(404);

        $planet = factory(Planet::class)->create([
            'user_id' => null,
            'x' => 10,
            'y' => 10,
        ]);

        $this->post("/api/movement/transport/{$planet->id}")
            ->assertStatus(403);

        $unit = factory(Unit::class)->create([
            'type' => Unit::TYPE_TRANSPORTER,
            'speed' => 100,
            'capacity' => 10,
        ]);

        $resource = factory(Resource::class)->create();

        $population = factory(Population::class)->create([
            'planet_id' => $user->current_id,
            'unit_id' => $unit->id,
            'quantity' => 5,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 11,
            'y' => 11,
        ]);

        $stock = factory(Stock::class)->create([
            'planet_id' => $user->current_id,
            'resource_id' => $resource->id,
            'quantity' => 5,
        ]);

        $this->post("/api/movement/transport/{$planet->id}", [
            'quantity' => [
                $resource->id => 10,
            ],
        ])->assertStatus(400);

        $population->update([
            'quantity' => 20,
        ]);

        $this->post("/api/movement/transport/{$planet->id}", [
            'quantity' => [
                $resource->id => 10,
            ],
        ])->assertStatus(400);

        $stock->update([
            'quantity' => 20,
        ]);

        $this->assertDatabaseMissing('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_TRANSPORT,
        ]);

        Bus::fake();

        $this->post("/api/movement/transport/{$planet->id}", [
            'quantity' => [
                $resource->id => 10,
            ],
        ])->assertStatus(200);

        Bus::assertDispatched(Move::class);

        $this->assertDatabaseHas('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_TRANSPORT,
        ]);
    }

    public function testStoreTrade()
    {
        $user = auth()->user();

        $this->post('/api/movement/trade/10')
            ->assertStatus(404);

        $this->post('/api/movement/trade/not-id')
            ->assertStatus(404);

        $planet = factory(Planet::class)->create([
            'user_id' => null,
            'x' => 12,
            'y' => 12,
        ]);

        $building = factory(Building::class)->create([
            'type' => Building::TYPE_TRAINER,
            'trade_time_bonus' => 0.5,
        ]);

        $grid = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building->id,
            'level' => 10,
        ]);

        $this->post("/api/movement/trade/{$grid->id}")
            ->assertStatus(403);

        $planet->update([
            'user_id' => $user->id,
        ]);

        $this->post("/api/movement/trade/{$grid->id}")
            ->assertStatus(403);

        $user->update([
            'capital_id' => $user->current_id,
        ]);

        $building->update([
            'type' => Building::TYPE_TRADER,
        ]);

        $unit = factory(Unit::class)->create([
            'type' => Unit::TYPE_TRANSPORTER,
            'speed' => 100,
            'capacity' => 10,
        ]);

        $resource = factory(Resource::class)->create();

        $population = factory(Population::class)->create([
            'planet_id' => $user->current_id,
            'unit_id' => $unit->id,
            'quantity' => 5,
        ]);

        factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 13,
            'y' => 13,
        ]);

        $stock = factory(Stock::class)->create([
            'planet_id' => $user->current_id,
            'resource_id' => $resource->id,
            'quantity' => 5,
        ]);

        $this->post("/api/movement/trade/{$grid->id}", [
            'quantity' => [
                $resource->id => 10,
            ],
        ])->assertStatus(400);

        $population->update([
            'quantity' => 20,
        ]);

        $this->post("/api/movement/transport/{$grid->id}", [
            'quantity' => [
                $resource->id => 10,
            ],
        ])->assertStatus(400);

        $stock->update([
            'quantity' => 20,
        ]);

        $this->assertDatabaseMissing('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_TRADE,
        ]);

        Bus::fake();

        $this->post("/api/movement/trade/{$grid->id}", [
            'quantity' => [
                $resource->id => 10,
            ],
        ])->assertStatus(200);

        Bus::assertDispatched(Move::class);

        $this->assertDatabaseHas('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_TRADE,
        ]);
    }

    public function testStorePatrol()
    {
        $user = auth()->user();

        $this->post('/api/movement/patrol/10')
            ->assertStatus(404);

        $this->post('/api/movement/patrol/not-id')
            ->assertStatus(404);

        $planet = factory(Planet::class)->create([
            'user_id' => null,
            'x' => 14,
            'y' => 14,
        ]);

        $building = factory(Building::class)->create([
            'type' => Building::TYPE_TRAINER,
            'trade_time_bonus' => 0.5,
        ]);

        $grid = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building->id,
            'level' => 10,
        ]);

        $this->post("/api/movement/patrol/{$grid->id}")
            ->assertStatus(403);

        $planet->update([
            'user_id' => $user->id,
        ]);

        $this->post("/api/movement/patrol/{$grid->id}")
            ->assertStatus(403);

        $user->update([
            'capital_id' => $user->current_id,
        ]);

        $building->update([
            'type' => Building::TYPE_TRADER,
        ]);

        $unit = factory(Unit::class)->create([
            'type' => Unit::TYPE_TRANSPORTER,
            'speed' => 100,
            'capacity' => 10,
        ]);

        $population = factory(Population::class)->create([
            'planet_id' => $user->current_id,
            'unit_id' => $unit->id,
            'quantity' => 5,
        ]);

        factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 15,
            'y' => 15,
        ]);

        $this->post("/api/movement/patrol/{$grid->id}", [
            'quantity' => [
                $unit->id => 10,
            ],
        ])->assertStatus(400);

        $population->update([
            'quantity' => 20,
        ]);

        $this->assertDatabaseMissing('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_PATROL,
        ]);

        Bus::fake();

        $this->post("/api/movement/patrol/{$grid->id}", [
            'quantity' => [
                $unit->id => 10,
            ],
        ])->assertStatus(200);

        Bus::assertDispatched(Move::class);

        $this->assertDatabaseHas('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_PATROL,
        ]);
    }
}
