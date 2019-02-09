<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\Population;
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

        $initialDispatcher = Event::getFacadeRoot();

        Event::fake();

        Model::setEventDispatcher($initialDispatcher);

        $user->update([
            'current_id' => $planet->id,
            'started_at' => Carbon::now(),
        ]);
    }

    public function testStoreScout()
    {
        $user = auth()->user();

        $unit = factory(Unit::class)->create([
            'type' => Unit::TYPE_SCOUT,
            'speed' => 100,
        ]);

        $population = factory(Population::class)->create([
            'planet_id' => $user->current_id,
            'unit_id' => $unit->id,
            'quantity' => 20,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => null,
        ]);

        $this->post('/api/movement/scout/10')
            ->assertStatus(404);

        $this->post('/api/movement/scout/not-id')
            ->assertStatus(404);

        $this->assertDatabaseMissing('movements', [
            'user_id' => $user->id,
            'type' => Movement::TYPE_SCOUT,
        ]);

        $this->post("/api/movement/scout/{$planet->id}", [
            'quantity' => 10,
        ])->assertStatus(200);

        // TODO

//        Event::assertDispatched(PlanetUpdated::class, function ($event) use ($user) {
//            return $event->startId === $user->current_id;
//        });
//
//        $this->assertDatabaseHas('movements', [
//            'user_id' => $user->id,
//            'type' => Movement::TYPE_SCOUT,
//        ]);
    }
}
