<?php

namespace Tests\Feature\Api;

use App\Models\ExpeditionLog;
use App\Models\Star;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\ExpeditionLogCreated;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ExpeditionLogTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'started_at' => Carbon::now(),
        ]);

        Passport::actingAs($user);
    }

    public function testIndex()
    {
        $user = auth()->user();

        $star = Star::factory()->create();

        $expeditionLog = ExpeditionLog::factory()->create([
            'user_id' => $user->id,
            'star_id' => $star->id,
        ]);

        $unit = Unit::factory()->create();

        $expeditionLog->units()->attach($unit->id, [
            'quantity' => 10,
        ]);

        $expeditionLog->user->notify(
            new ExpeditionLogCreated($expeditionLog->id)
        );

        $this->assertEquals($user->notifications()
            ->where('type', ExpeditionLogCreated::class)
            ->count(), 1);

        $this->getJson('/api/expedition-log')->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'star',
                        'solarion',
                        'experience',
                        'created_at',
                        'units' => [
                            [
                                'id',
                                'name',
                                'description',
                                'quantity',
                            ],
                        ],
                    ],
                ],
            ])->assertJson([
                'data' => [
                    [
                        'id' => $expeditionLog->id,
                        'star' => $star->name,
                        'solarion' => $expeditionLog->solarion,
                        'experience' => $expeditionLog->experience,
                        'created_at' => $expeditionLog->created_at->toDateTimeString(),
                        'units' => [
                            [
                                'id' => $unit->id,
                                'name' => $unit->translation('name'),
                                'description' => $unit->translation('description'),
                                'quantity' => 10,
                            ],
                        ],
                    ],
                ],
            ]);

        $this->assertEquals($user->notifications()
            ->where('type', ExpeditionLogCreated::class)
            ->count(), 0);
    }
}
