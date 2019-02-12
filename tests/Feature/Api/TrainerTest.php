<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Bookmark;
use Koodilab\Models\Building;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\Star;
use Koodilab\Models\Training;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TrainerTest extends TestCase
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
        $planet = factory(Planet::class)->create([
            'user_id' => auth()->user()->id,
        ]);

        $building = factory(Building::class)->create([
            'type' => Building::TYPE_TRAINER,
            'train_time_bonus' => 5,
            'end_level' => 1,
        ]);

        $grid = factory(Grid::class)->create([
            'building_id' => $building->id,
            'planet_id' => $planet->id,
        ]);

        $unit = factory(Unit::class)->create([
            'train_time' => 10,
        ]);

        $training = factory(Training::class)->create([
            'grid_id' => $grid->id,
            'unit_id' => $unit->id,
        ]);

        $this->getJson("/api/trainer/{$grid->id}")->assertStatus(200)
            ->assertJsonStructure([
                'remaining',
                'quantity',
                'units' => [
                    [
                        'id',
                        'name',
                        'type',
                        'speed',
                        'attack',
                        'defense',
                        'supply',
                        'train_cost',
                        'train_time',
                        'description',
                        'detection',
                        'capacity',
                        'research_experience',
                        'research_cost',
                        'research_time',
                    ]
                ],
            ])->assertJson([
                'remaining' => $training->remaining,
                'quantity' => $training->quantity,
                'units' => [
                    [
                        'id' => $unit->id,
                        'name' => $unit->translation('name'),
                        'type' => $unit->type,
                        'speed' => $unit->speed,
                        'attack' => $unit->attack,
                        'defense' => $unit->defense,
                        'supply' => $unit->supply,
                        'train_cost' => $unit->train_cost,
                        'train_time' => 0,
                        'description' => $unit->translation('description'),
                        'detection' => $unit->detection,
                        'capacity' => $unit->capacity,
                        'research_experience' => $unit->research_experience,
                        'research_cost' => $unit->research_cost,
                        'research_time' => $unit->research_time,
                    ]
                ],
            ]);
    }

//    public function testStore()
//    {
//        $star = factory(Star::class)->create();
//
//        $bookmark = factory(Bookmark::class)->create([
//            'user_id' => auth()->user()->id,
//            'star_id' => $star->id,
//        ]);
//
//        $this->post('/api/bookmark/10')
//            ->assertStatus(404);
//
//        $this->post('/api/bookmark/not-id')
//            ->assertStatus(404);
//
//        $this->post("/api/bookmark/{$star->id}")
//            ->assertStatus(200);
//
//        $this->assertDatabaseHas('bookmarks', [
//            'id' => $bookmark->id,
//        ]);
//    }
//
//    public function testDestroy()
//    {
//        $bookmark = factory(Bookmark::class)->create([
//            'user_id' => auth()->user()->id,
//        ]);
//
//        $this->delete('/api/bookmark/10')
//            ->assertStatus(404);
//
//        $this->delete('/api/bookmark/not-id')
//            ->assertStatus(404);
//
//        $this->assertDatabaseHas('bookmarks', [
//            'id' => $bookmark->id,
//        ]);
//
//        $this->delete("/api/bookmark/{$bookmark->id}")
//            ->assertStatus(200);
//
//        $this->assertDatabaseMissing('bookmarks', [
//            'id' => $bookmark->id,
//        ]);
//    }
}
