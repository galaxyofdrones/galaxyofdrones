<?php

namespace Koodilab\Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\User;
use Koodilab\Tests\TestCase;
use Laravel\Passport\Passport;

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
