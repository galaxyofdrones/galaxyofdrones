<?php

namespace Tests\Feature\Api;

use App\Models\Planet;
use App\Models\Resource;
use App\Models\Star;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $star = Star::factory()->create();

        $resource = Resource::factory()->create([
            'is_unlocked' => false,
        ]);

        Planet::factory()->create([
            'user_id' => null,
            'resource_id' => $resource->id,
            'x' => 1,
            'y' => 1,
        ]);

        Planet::factory()->create([
            'resource_id' => $resource->id,
            'x' => 2,
            'y' => 2,
        ]);

        $resource2 = Resource::factory()->create([
            'is_unlocked' => true,
        ]);

        Planet::factory()->create([
            'user_id' => null,
            'resource_id' => $resource2->id,
            'size' => Planet::SIZE_SMALL,
            'x' => 3,
            'y' => 3,
        ]);

        $this->getJson('/api/status')->assertStatus(200)
            ->assertJsonStructure([
                'starmap' => [
                    'star_count',
                    'started_at',
                    'planet' => [
                        'free_count',
                        'occupied_count',
                        'starter_count',
                    ],
                ],
            ])->assertJson([
                'starmap' => [
                    'star_count' => 1,
                    'started_at' => $star->created_at->toDateTimeString(),
                    'planet' => [
                        'free_count' => 2,
                        'occupied_count' => 1,
                        'starter_count' => 1,
                    ],
                ],
            ]);
    }
}
