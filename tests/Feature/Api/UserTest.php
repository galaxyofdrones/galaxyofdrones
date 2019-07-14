<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function testIfNotPlayer()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(400);
    }

    public function testIndex()
    {
        $user = auth()->user();

        $this->getJson('/api/user')->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'username',
                'email',
                'energy',
                'production_rate',
                'level',
                'experience',
                'level_experience',
                'next_level_experience',
                'notification_count',
                'gravatar',
            ])->assertJson([
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'energy' => $user->energy,
                'production_rate' => $user->production_rate,
                'level' => $user->level,
                'experience' => $user->experience,
                'level_experience' => $user->level_experience,
                'next_level_experience' => $user->next_level_experience,
                'notification_count' => $user->notifications()->count(),
                'gravatar' => $user->gravatar([
                    's' => 100,
                ]),
            ]);
    }

    public function testCapital()
    {
        $user = auth()->user();

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 1,
            'y' => 1,
        ]);

        $user->update([
            'capital_id' => $planet->id,
        ]);

        $this->getJson('/api/user/capital')->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'capital_id',
                'capital_change_remaining',
                'incoming_capital_movement_count',
                'planets' => [
                    [
                        'id',
                        'name',
                    ],
                ],
            ])->assertJson([
                'id' => $user->id,
                'capital_id' => $planet->id,
                'capital_change_remaining' => $user->capital_change_remaining,
                'incoming_capital_movement_count' => $planet->incomingCapitalMovementCount(),
                'planets' => [
                    [
                        'id' => $planet->id,
                        'name' => $planet->display_name,
                    ],
                ],
            ]);
    }

    public function testShow()
    {
        $user = factory(User::class)->create();
        $loggedUser = auth()->user();

        $this->getJson("/api/user/{$user->id}")->assertStatus(400);

        $user->update([
            'started_at' => Carbon::now(),
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 2,
            'y' => 2,
        ]);

        $this->getJson("/api/user/{$user->id}")->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'username',
                'username_with_level',
                'experience',
                'mission_count',
                'expedition_count',
                'planet_count',
                'winning_battle_count',
                'losing_battle_count',
                'created_at',
                'can_block',
                'is_blocked',
                'is_blocked_by',
                'planets' => [
                    [
                        'id',
                        'resource_id',
                        'name',
                        'x',
                        'y',
                    ],
                ],
            ])->assertJson([
                'id' => $user->id,
                'username' => $user->username,
                'username_with_level' => "{$user->username} (Level {$user->level})",
                'experience' => $user->experience,
                'mission_count' => $user->missionLogs()->count(),
                'expedition_count' => $user->expeditionLogs()->count(),
                'planet_count' => $user->planets()->count(),
                'winning_battle_count' => $user->winningBattleLogCount(),
                'losing_battle_count' => $user->losingBattleLogCount(),
                'created_at' => $user->created_at->toDateTimeString(),
                'can_block' => $loggedUser->id != $user->id,
                'is_blocked' => ! empty($loggedUser->findByBlocked($user)),
                'is_blocked_by' => ! empty($user->findByBlocked($loggedUser)),
                'planets' => [
                    [
                        'id' => $planet->id,
                        'resource_id' => $planet->resource_id,
                        'name' => $planet->display_name,
                        'x' => $planet->x,
                        'y' => $planet->y,
                    ],
                ],
            ]);
    }

    public function testUpdate()
    {
        $user = auth()->user();

        $email = 'this_is_the_best_game@koodilab.com';

        $this->assertNotEquals($user->email, $email);

        $this->put('/api/user', [
            'email' => $email,
            'is_notification_enabled' => true,
        ])->assertStatus(200);

        $this->assertEquals($user->email, $email);
    }

    public function testUpdateCapital()
    {
        $user = auth()->user();

        $planet = factory(Planet::class)->create([
            'user_id' => null,
            'x' => 3,
            'y' => 3,
        ]);

        $this->put('/api/user/capital/14')->assertStatus(404);
        $this->put('/api/user/capital/not-id')->assertStatus(404);
        $this->put("/api/user/capital/{$planet->id}")->assertStatus(403);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 4,
            'y' => 4,
        ]);

        $this->put("/api/user/capital/{$planet->id}")->assertStatus(400);

        $capital = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 5,
            'y' => 5,
        ]);

        $user->update([
            'capital_id' => $capital->id,
        ]);

        $user->update([
            'last_capital_changed' => Carbon::now()->subMonths(2),
        ]);

        $movement = factory(Movement::class)->create([
            'end_id' => $capital->id,
            'type' => Movement::TYPE_PATROL,
        ]);

        $this->put("/api/user/capital/{$planet->id}")->assertStatus(400);

        $movement->update([
            'end_id' => factory(Planet::class)->create([
                'x' => 6,
                'y' => 6,
            ])->id,
        ]);

        $this->put("/api/user/capital/{$planet->id}")->assertStatus(200);
        $this->assertEquals($planet->user->capital->id, $planet->id);
    }

    public function testUpdateCurrent()
    {
        $user = auth()->user();

        $planet = factory(Planet::class)->create([
            'user_id' => null,
            'x' => 7,
            'y' => 7,
        ]);

        $this->put('/api/user/current/14')->assertStatus(404);
        $this->put('/api/user/current/not-id')->assertStatus(404);
        $this->put("/api/user/current/{$planet->id}")->assertStatus(403);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 8,
            'y' => 8,
        ]);

        $this->put("/api/user/current/{$planet->id}")->assertStatus(200);
        $this->assertEquals($planet->user->current->id, $planet->id);
    }
}
