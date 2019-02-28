<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\BattleLog;
use Koodilab\Models\Building;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BattleLogTest extends TestCase
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

        $battleLog = factory(BattleLog::class)->create([
            'attacker_id' => $user->id,
        ]);

        $resource = factory(Resource::class)->create();
        $battleLog->resources()->attach($resource, [
            'quantity' => 10,
            'losses' => 5,
        ]);

        $building = factory(Building::class)->create();
        $battleLog->buildings()->attach($building, [
            'level' => 10,
            'losses' => 5,
        ]);

        $attackerUnit = factory(Unit::class)->create();
        $battleLog->attackerUnits()->attach($attackerUnit, [
            'owner' => BattleLog::OWNER_ATTACKER,
            'quantity' => 10,
            'losses' => 5,
        ]);

        $defenderUnit = factory(Unit::class)->create();
        $battleLog->defenderUnits()->attach($defenderUnit, [
            'owner' => BattleLog::OWNER_DEFENDER,
            'quantity' => 10,
            'losses' => 5,
        ]);

        $this->getJson('/api/battle-log')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'type',
                        'winner',
                        'created_at',
                        'is_attack',
                        'is_defense',
                        'start' => [
                            'id',
                            'resource_id',
                            'name',
                        ],
                        'end' => [
                            'id',
                            'resource_id',
                            'name',
                        ],
                        'attacker' => [
                            'id',
                            'username',
                        ],
                        'defender' => [
                            'id',
                            'username',
                        ],
                        'resources' => [
                            [
                                'id',
                                'name',
                                'description',
                                'quantity',
                                'losses',
                            ],
                        ],
                        'buildings' => [
                            [
                                'id',
                                'name',
                                'description',
                                'level',
                                'losses',
                            ],
                        ],
                        'attacker_units' => [
                            [
                                'id',
                                'name',
                                'description',
                                'quantity',
                                'losses',
                            ],
                        ],
                        'defender_units' => [
                            [
                                'id',
                                'name',
                                'description',
                                'quantity',
                                'losses',
                            ],
                        ],
                    ],
                ],
            ])->assertJson([
                'data' => [
                    [
                        'id' => $battleLog->id,
                        'type' => $battleLog->type,
                        'winner' => $battleLog->winner,
                        'created_at' => $battleLog->created_at->toDateTimeString(),
                        'is_attack' => true,
                        'is_defense' => false,
                        'start' => [
                            'id' => $battleLog->start_id,
                            'resource_id' => $battleLog->start->resource_id,
                            'name' => $battleLog->start_name,
                        ],
                        'end' => [
                            'id' => $battleLog->end_id,
                            'resource_id' => $battleLog->end->resource_id,
                            'name' => $battleLog->end_name,
                        ],
                        'attacker' => [
                            'id' => $battleLog->attacker_id,
                            'username' => $battleLog->attacker->username,
                        ],
                        'defender' => [
                            'id' => $battleLog->defender_id,
                            'username' => $battleLog->defender->username,
                        ],
                        'resources' => [
                            [
                                'id' => $resource->id,
                                'name' => $resource->translation('name'),
                                'description' => $resource->translation('description'),
                                'quantity' => 10,
                                'losses' => 5,
                            ],
                        ],
                        'buildings' => [
                            [
                                'id' => $building->id,
                                'name' => $building->translation('name'),
                                'description' => $building->translation('description'),
                                'level' => 10,
                                'losses' => 5,
                            ],
                        ],
                        'attacker_units' => [
                            [
                                'id' => $attackerUnit->id,
                                'name' => $attackerUnit->translation('name'),
                                'description' => $attackerUnit->translation('description'),
                                'quantity' => 10,
                                'losses' => 5,
                            ],
                        ],
                        'defender_units' => [
                            [
                                'id' => $defenderUnit->id,
                                'name' => $defenderUnit->translation('name'),
                                'description' => $defenderUnit->translation('description'),
                                'quantity' => 10,
                                'losses' => 5,
                            ],
                        ],
                    ],
                ],
            ]);
    }
}
