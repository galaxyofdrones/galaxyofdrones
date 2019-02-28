<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\Bookmark;
use Koodilab\Models\Star;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BookmarkTest extends TestCase
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
        $star = factory(Star::class)->create();

        $bookmark = factory(Bookmark::class)->create([
            'name' => 'Favorite',
            'user_id' => auth()->user()->id,
            'star_id' => $star->id,
        ]);

        $this->getJson('/api/bookmark')->assertStatus(200)
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
                        'name',
                        'x',
                        'y',
                        'created_at',
                    ],
                ],
            ])->assertJson([
                'data' => [
                    [
                        'id' => $bookmark->id,
                        'name' => $bookmark->name,
                        'x' => $bookmark->star->x,
                        'y' => $bookmark->star->y,
                        'created_at' => $bookmark->created_at,
                    ],
                ],
            ]);
    }

    public function testStore()
    {
        $star = factory(Star::class)->create();

        $bookmark = factory(Bookmark::class)->create([
            'user_id' => auth()->user()->id,
            'star_id' => $star->id,
        ]);

        $this->post('/api/bookmark/10')
            ->assertStatus(404);

        $this->post('/api/bookmark/not-id')
            ->assertStatus(404);

        $this->post("/api/bookmark/{$star->id}")
            ->assertStatus(200);

        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id,
        ]);
    }

    public function testDestroy()
    {
        $bookmark = factory(Bookmark::class)->create([
            'user_id' => auth()->user()->id,
        ]);

        $this->delete('/api/bookmark/10')
            ->assertStatus(404);

        $this->delete('/api/bookmark/not-id')
            ->assertStatus(404);

        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id,
        ]);

        $this->delete("/api/bookmark/{$bookmark->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('bookmarks', [
            'id' => $bookmark->id,
        ]);
    }
}
