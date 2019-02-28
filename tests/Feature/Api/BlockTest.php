<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BlockTest extends TestCase
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

    public function testUpdate()
    {
        $user = factory(User::class)->create([
            'started_at' => Carbon::now(),
        ]);

        $current = auth()->user();

        $this->put('/api/block')
            ->assertStatus(404);

        $this->put('/api/block/not-id')
            ->assertStatus(404);

        $this->put("/api/block/{$user->id}")
            ->assertStatus(200);

        $this->assertDatabaseHas('blocks', [
            'blocked_id' => $user->id,
            'user_id' => $current->id,
        ]);

        $this->put("/api/block/{$user->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('blocks', [
            'blocked_id' => $user->id,
            'user_id' => $current->id,
        ]);
    }
}
