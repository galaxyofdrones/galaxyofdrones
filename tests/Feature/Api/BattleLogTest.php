<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\BattleLog;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BattleLogTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $user = factory(User::class)->create([
            'started_at' => Carbon::now(),
        ]);

        Passport::actingAs($user);

        $battleLog = factory(BattleLog::class)->create([
            'attacker_id' => $user->id,
        ]);
    }

    public function testIndex()
    {
        $this->getJson('/api/battle-log')
            ->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
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
                        'resources',
                        'buildings',
                        'attacker_units',
                        'defender_units',
                    ],
                ],
            ]);
    }
}
