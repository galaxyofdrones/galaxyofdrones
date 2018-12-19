<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testIfNotPlayer()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(400);
    }
}
