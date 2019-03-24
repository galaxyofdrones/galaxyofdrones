<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MonitorTest extends TestCase
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

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 1,
            'y' => 1,
        ]);

        factory(Movement::class)->create([
            'end_id' => $planet->id,
            'type' => Movement::TYPE_ATTACK,
        ]);

        $this->getJson('/api/monitor')->assertStatus(200)
            ->assertJsonStructure([
                'incoming',
            ])->assertJson([
                'incoming' => 1,
            ]);
    }

    public function testShow()
    {
        $user = auth()->user();

        $startPlanet = factory(Planet::class)->create([
            'x' => 2,
            'y' => 2,
        ]);

        $endPlanet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 3,
            'y' => 3,
        ]);

        $movement = factory(Movement::class)->create([
            'start_id' => $startPlanet->id,
            'end_id' => $endPlanet->id,
            'type' => Movement::TYPE_ATTACK,
        ]);

        $this->getJson('/api/monitor/show')->assertStatus(200)
            ->assertJsonStructure([
                'incoming_movements' => [
                    [
                        'id',
                        'type',
                        'remaining',
                        'start' => [
                            'id',
                            'resource_id',
                            'display_name',
                            'x',
                            'y',
                        ],
                        'end' => [
                            'id',
                            'resource_id',
                            'display_name',
                            'x',
                            'y',
                        ],
                    ],
                ],
            ])->assertJson([
                'incoming_movements' => [
                    [
                        'id' => $movement->id,
                        'type' => $movement->type,
                        'remaining' => $movement->remaining,
                        'start' => [
                            'id' => $startPlanet->id,
                            'resource_id' => $startPlanet->resource_id,
                            'display_name' => $startPlanet->display_name,
                            'x' => $startPlanet->x,
                            'y' => $startPlanet->y,
                        ],
                        'end' => [
                            'id' => $endPlanet->id,
                            'resource_id' => $endPlanet->resource_id,
                            'display_name' => $endPlanet->display_name,
                            'x' => $endPlanet->x,
                            'y' => $endPlanet->y,
                        ],
                    ],
                ],
            ]);
    }
}
