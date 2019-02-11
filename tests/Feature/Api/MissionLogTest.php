<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\ExpeditionLog;
use Koodilab\Models\MissionLog;
use Koodilab\Models\Resource;
use Koodilab\Models\Star;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Koodilab\Notifications\MissionLogCreated;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MissionLogTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
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

        $missionLog = factory(MissionLog::class)->create([
            'user_id' => $user->id,
        ]);

        $resource = factory(Resource::class)->create();

        $missionLog->resources()->attach($resource->id, [
            'quantity' => 10,
        ]);

        $missionLog->user->notify(
            new MissionLogCreated($missionLog->id)
        );

        $this->assertEquals($user->notifications()
            ->where('type', MissionLogCreated::class)
            ->count(), 1);

        $this->get('/api/mission-log')->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
//                    [
//                        'id',
//                        'star',
//                        'solarion',
//                        'experience',
//                        'created_at',
//                        'units' => [
//                            'id',
//                            'name',
//                            'description',
//                            'quantity',
//                        ],
//                    ],
                ],
            ])->assertJson([
                'data' => [
//                    [
//                        'id' => $expeditionLog->id,
//                        'star' => $star->name,
//                        'solarion' => $expeditionLog->solarion,
//                        'experience' => $expeditionLog->experience,
//                        'created_at' => $expeditionLog->created_at->toDateTimeString(),
//                        'units' => [
//                            'id' => $unit->id,
//                            'name' => $unit->translation('name'),
//                            'description' => $unit->translation('description'),
//                            'quantity' => 10,
//                        ],
//                    ],
                ],
            ]);

        $this->assertEquals($user->notifications()
            ->where('type', MissionLogCreated::class)
            ->count(), 0);
    }
}
