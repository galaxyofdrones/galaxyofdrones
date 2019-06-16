<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Koodilab\Models\User;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $this->post('/api/donation')->assertStatus(400);

        $this->post('/api/donation', [
            'key' => 'invalidkey',
        ])->assertStatus(400);

        $this->post('/api/donation', [
            'key' => 'invalidkey',
            'email' => 'invalidemail',
        ])->assertStatus(400);

        $this->post('/api/donation', [
            'key' => 'testkey',
            'email' => 'invalidemail',
        ])->assertStatus(400);

        factory(User::class)->create([
            'email' => 'support@koodilab.com',
            'started_at' => Carbon::now(),
        ]);

        $this->post('/api/donation', [
            'key' => 'testkey',
            'email' => 'support@koodilab.com',
        ])->assertStatus(200);

        $this->post('/api/donation', [
            'key' => 'testkey',
            'email' => 'support@koodilab.com',
        ])->assertStatus(400);

        config()->set('donation.key', null);

        $this->post('/api/donation')->assertStatus(404);
    }
}
