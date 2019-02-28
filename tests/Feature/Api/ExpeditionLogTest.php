<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\ExpeditionLog;
use Koodilab\Models\Star;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Koodilab\Notifications\ExpeditionLogCreated;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ExpeditionLogTest extends TestCase
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

        $star = factory(Star::class)->create();

        $expeditionLog = factory(ExpeditionLog::class)->create([
            'user_id' => $user->id,
            'star_id' => $star->id,
        ]);

        $unit = factory(Unit::class)->create();

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
