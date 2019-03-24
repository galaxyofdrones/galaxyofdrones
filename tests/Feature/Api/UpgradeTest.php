<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Bus;
use Koodilab\Jobs\Upgrade as UpgradeJob;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\Training;
use Koodilab\Models\Upgrade;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpgradeTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = factory(User::class)->create([
            'started_at' => Carbon::now(),
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 2,
            'y' => 1,
        ]);

        Passport::actingAs($user);

        $user->update([
            'capital_id' => $planet->id,
            'current_id' => $planet->id,
        ]);
    }

    public function testIndex()
    {
        $user = auth()->user();

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 5,
            'y' => 1,
        ]);

        $building = factory(Building::class)->create([
            'end_level' => 100,
            'defense_bonus' => 0,
            'construction_experience' => 0,
            'construction_cost' => 0,
            'construction_time' => 0,
            'construction_time_bonus' => 0,
            'production_rate' => 0,
            'type' => Building::TYPE_CENTRAL,
        ]);

        $grid = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building->id,
        ]);

        $upgrade = factory(Upgrade::class)->create([
            'grid_id' => $grid->id,
        ]);

        factory(Training::class)->create([
            'grid_id' => $grid->id,
        ]);

        $modifiedLevel = $grid->level + 1;

        $this->getJson("/api/upgrade/{$grid->id}")->assertStatus(200)
            ->assertJsonStructure([
                'has_training',
                'remaining',
                'building' => [
                    'id',
                    'name',
                    'name_with_level',
                    'type',
                    'construction_experience',
                    'construction_cost',
                    'construction_time',
                    'description',
                    'defense',
                    'detection',
                    'capacity',
                    'supply',
                    'mining_rate',
                    'production_rate',
                    'defense_bonus',
                    'construction_time_bonus',
                    'trade_time_bonus',
                    'train_time_bonus',
                    'has_lower_level',
                ],
                'upgrade' => [
                    'id',
                    'name',
                    'name_with_level',
                    'type',
                    'construction_experience',
                    'construction_cost',
                    'construction_time',
                    'description',
                    'defense',
                    'detection',
                    'capacity',
                    'supply',
                    'mining_rate',
                    'production_rate',
                    'defense_bonus',
                    'construction_time_bonus',
                    'trade_time_bonus',
                    'train_time_bonus',
                    'has_lower_level',
                ],
            ])->assertJson([
                'has_training' => true,
                'remaining' => $upgrade->remaning,
                'building' => [
                    'id' => $building->id,
                    'name' => $building->name['en'],
                    'name_with_level' => "{$building->name['en']} (Level {$grid->level})",
                    'type' => $building->type,
                    'construction_experience' => $building->construction_experience,
                    'construction_cost' => $building->construction_cost,
                    'construction_time' => $building->construction_time,
                    'description' => $building->description['en'],
                    'defense' => $building->defense,
                    'detection' => $building->detection,
                    'capacity' => $building->capacity,
                    'supply' => $building->supply,
                    'mining_rate' => $building->mining_rate,
                    'production_rate' => $building->production_rate,
                    'defense_bonus' => $building->defense_bonus,
                    'construction_time_bonus' => $building->construction_time_bonus,
                    'trade_time_bonus' => $building->trade_time_bonus,
                    'train_time_bonus' => $building->train_time_bonus,
                    'has_lower_level' => true,
                ],
                'upgrade' => [
                    'id' => null,
                    'name' => $building->name['en'],
                    'name_with_level' => "{$building->name['en']} (Level {$modifiedLevel})",
                    'type' => $building->type,
                    'construction_experience' => $building->construction_experience,
                    'construction_cost' => $building->construction_cost,
                    'construction_time' => $building->construction_time,
                    'description' => $building->description['en'],
                    'defense' => $building->defense,
                    'detection' => $building->detection,
                    'capacity' => $building->capacity,
                    'supply' => $building->supply,
                    'mining_rate' => $building->mining_rate,
                    'production_rate' => $building->production_rate,
                    'defense_bonus' => $building->defense_bonus,
                    'construction_time_bonus' => $building->construction_time_bonus,
                    'trade_time_bonus' => $building->trade_time_bonus,
                    'train_time_bonus' => $building->train_time_bonus,
                    'has_lower_level' => true,
                ],
            ]);
    }

    public function testIndexAll()
    {
        $user = auth()->user();

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 2,
            'y' => 6,
        ]);

        $user->update([
            'current_id' => $planet->id,
            'solarion' => 2,
        ]);

        $building1 = factory(Building::class)->create([
            'end_level' => 20,
            'construction_cost' => 20,
        ]);

        $grid1 = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building1->id,
            'level' => 9,
            'x' => 9,
            'y' => 6,
        ]);

        $building2 = factory(Building::class)->create([
            'end_level' => 20,
            'construction_cost' => 100,
        ]);

        factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building2->id,
            'level' => 9,
            'x' => 8,
            'y' => 16,
        ]);

        factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => null,
        ]);

        $this->getJson('/api/upgrade/all')->assertStatus(200)
            ->assertJsonStructure([
                'has_solarion',
                'upgrade_cost',
            ])->assertJson([
                'has_solarion' => true,
                'upgrade_cost' => 30,
            ]);

        factory(Upgrade::class)->create([
            'grid_id' => $grid1->id,
        ]);

        $this->getJson('/api/upgrade/all')->assertStatus(200)
            ->assertJsonStructure([
                'has_solarion',
                'upgrade_count',
                'upgrade_cost',
            ])->assertJson([
                'has_solarion' => true,
                'upgrade_count' => 1,
                'upgrade_cost' => 25,
            ]);
    }

    public function testStore()
    {
        $user = auth()->user();

        $user->update([
            'energy' => 100,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 8,
            'y' => 1,
        ]);

        $building = factory(Building::class)->create([
            'end_level' => 0,
            'construction_cost' => 150,
        ]);

        $grid = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => null,
        ]);

        $this->post('/api/upgrade/10')
            ->assertStatus(404);

        $this->post('/api/upgrade/not-id')
            ->assertStatus(404);

        $this->post("/api/upgrade/{$grid->id}")
            ->assertStatus(400);

        $grid->update([
            'building_id' => $building->id,
        ]);

        $upgrade = factory(Upgrade::class)->create([
            'grid_id' => $grid->id,
        ]);

        $this->post("/api/upgrade/{$grid->id}")
            ->assertStatus(400);

        $upgrade->delete();

        $this->post("/api/upgrade/{$grid->id}")
            ->assertStatus(400);

        $building->update([
            'end_level' => 100,
        ]);

        $this->post("/api/upgrade/{$grid->id}")
            ->assertStatus(200);
    }

    public function testStoreAll()
    {
        $user = auth()->user();

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 21,
            'y' => 11,
        ]);

        $user->update([
            'current_id' => $planet->id,
            'energy' => 28,
            'solarion' => 0,
        ]);

        $building1 = factory(Building::class)->create([
            'end_level' => 20,
            'construction_cost' => 20,
        ]);

        $grid1 = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building1->id,
            'level' => 9,
            'x' => 14,
            'y' => 12,
        ]);

        $building2 = factory(Building::class)->create([
            'end_level' => 20,
            'construction_cost' => 100,
        ]);

        factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building2->id,
            'level' => 9,
            'x' => 20,
            'y' => 11,
        ]);

        factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => null,
        ]);

        $this->post('/api/upgrade/all')
            ->assertStatus(400);

        factory(Upgrade::class)->create([
            'grid_id' => $grid1->id,
        ]);

        $this->post('/api/upgrade/all')
            ->assertStatus(400);

        $user->update([
            'solarion' => 1,
        ]);

        Bus::fake();

        $this->post('/api/upgrade/all')
            ->assertStatus(200);

        Bus::assertDispatched(UpgradeJob::class);

        $this->assertEquals($user->energy, 3);
        $this->assertEquals($user->solarion, 0);
    }

    public function testDestroy()
    {
        $user = auth()->user();

        $user->update([
            'energy' => 100,
        ]);

        $planet = factory(Planet::class)->create([
            'user_id' => $user->id,
            'x' => 12,
            'y' => 15,
        ]);

        $building = factory(Building::class)->create([
            'end_level' => 50,
            'construction_time' => 200,
            'construction_cost' => 300,
        ]);

        $grid = factory(Grid::class)->create([
            'planet_id' => $planet->id,
            'building_id' => $building->id,
            'level' => 10,
        ]);

        $this->delete('/api/upgrade/10')
            ->assertStatus(404);

        $this->delete('/api/upgrade/not-id')
            ->assertStatus(404);

        $this->delete("/api/upgrade/{$grid->id}")
            ->assertStatus(400);

        $upgrade = factory(Upgrade::class)->create([
            'grid_id' => $grid->id,
        ]);

        $this->assertDatabaseHas('upgrades', [
            'id' => $upgrade->id,
        ]);

        $this->delete("/api/upgrade/{$grid->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('upgrades', [
            'id' => $upgrade->id,
        ]);
    }
}
