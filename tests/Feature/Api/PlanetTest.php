<?php

namespace Koodilab\Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Koodilab\Events\UserUpdated;
use Koodilab\Models\Planet;
use Koodilab\Models\User;
use Koodilab\Tests\TestCase;
use Laravel\Passport\Passport;

class PlanetTest extends TestCase
{
    use DatabaseMigrations;

    public function testCurrent()
    {
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

        $user->resources()->sync($planet->resource_id);

        $response = $this->getJson('/api/planet');

        $response->assertStatus(200)
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
}
