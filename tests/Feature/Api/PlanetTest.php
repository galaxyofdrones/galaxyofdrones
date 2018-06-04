<?php

namespace Koodilab\Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Events\UserUpdated;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\User;
use Koodilab\Tests\TestCase;
use Laravel\Passport\Passport;

class PlanetTest extends TestCase
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
        $this->getJson('/api/planet')
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'resource_id',
                'user_id',
                'name',
                'display_name',
                'x',
                'y',
                'capacity',
                'supply',
                'solarion',
                'mining_rate',
                'production_rate',
                'incoming_movement',
                'incoming_attack_movement',
                'outgoing_movement',
                'outgoing_attack_movement',
                'construction',
                'upgrade',
                'training',
                'used_capacity',
                'used_supply',
                'used_training_supply',
                'planets',
                'resources',
                'units',
                'grids',
            ])->assertJson([
                'name' => 'Earth',
                'display_name' => 'Earth',
            ]);
    }

    public function testCapital()
    {
        $capitalId = auth()->user()->capital_id;

        $this->getJson('/api/planet/capital')
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'resource_id',
                'user_id',
                'resource_count',
                'username',
                'can_occupy',
                'has_shield',
                'travel_time',
            ])->assertJson([
                'id' => $capitalId,
                'resource_id' => $capitalId,
            ]);
    }

    public function testShow()
    {
        $this->get('/api/planet/10')
            ->assertStatus(404);

        $this->get('/api/planet/not-id')
            ->assertStatus(404);

        $currentId = auth()->user()->current_id;

        $this->getJson("/api/planet/{$currentId}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'resource_id',
                'user_id',
                'resource_count',
                'username',
                'can_occupy',
                'has_shield',
                'travel_time',
            ])->assertJson([
                'id' => $currentId,
                'resource_id' => $currentId,
            ]);
    }

    public function testUpdateName()
    {
        $this->put('/api/planet/name')
            ->assertStatus(400);

        $this->put('/api/planet/name', [
            'name' => 'Helios',
        ])->assertStatus(200);

        $current = auth()->user()->current;

        Event::assertDispatched(PlanetUpdated::class, function ($event) use ($current) {
            return $event->planetId === $current->id;
        });

        $this->getJson('/api/planet')
            ->assertStatus(200)
            ->assertJson([
                'name' => $current->name,
                'display_name' => 'Helios',
            ]);
    }

    public function testDemolish()
    {
        $this->delete('/api/planet/demolish/not-id')
            ->assertStatus(404);

        $grid = factory(Grid::class)->create([
            'building_id' => null,
            'level' => null,
        ]);

        $this->delete("/api/planet/demolish/{$grid->id}")
            ->assertStatus(403);

        $grid->update([
            'planet_id' => auth()->user()->current_id,
        ]);

        $this->delete("/api/planet/demolish/{$grid->id}")
            ->assertStatus(400);

        $building = factory(Building::class)->create([
            'type' => Building::TYPE_CENTRAL,
        ]);

        $grid->update([
            'building_id' => $building->id,
            'level' => 1,
        ]);

        $this->delete("/api/planet/demolish/{$grid->id}")
            ->assertStatus(400);

        $building->update([
            'type' => Building::TYPE_MINER,
        ]);

        $this->delete("/api/planet/demolish/{$grid->id}")
            ->assertStatus(200);
    }
}
