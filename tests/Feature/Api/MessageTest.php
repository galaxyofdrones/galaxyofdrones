<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Koodilab\Models\Message;
use Koodilab\Models\User;
use Koodilab\Notifications\MessageSended;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $user = factory(User::class)->create([
            'started_at' => Carbon::now(),
        ]);

        Passport::actingAs($user);

        $sender = factory(User::class)->create([
            'id' => 2,
            'username' => 'Mike',
            'started_at' => Carbon::now(),
        ]);

        $message = factory(Message::class)->create([
            'id' => 1,
            'message' => 'Test message',
            'recipient_id' => $user->id,
            'sender_id' => $sender->id,
            'created_at' => Carbon::create(2018, 1, 1, 19),
        ]);

        Notification::fake();

        $user->notify(new MessageSended($message->id));

        Notification::assertSentTo($user, MessageSended::class);
    }

    public function testIndex()
    {
        $user = auth()->user();
        $message = $user->messages()->first();

        $this->getJson('/api/message')->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'message',
                        'created_at',
                        'sender' => [
                            'id',
                            'username',
                            'isBlocked',
                            'isBlockedBy',
                        ],
                    ],
                ],
            ])->assertJson([
                'data' => [
                    [
                        'id' => 1,
                        'message' => 'Test message',
                        'created_at' => Carbon::create(2018, 1, 1, 19),
                        'sender' => [
                            'id' => 2,
                            'username' => 'Mike',
                            'isBlocked' => false,
                            'isBlockedBy' => false,
                        ],
                    ],
                ],
            ]);
    }

    public function testStore()
    {
        $user = auth()->user();

        $this->assertDatabaseMissing('messages', [
            'sender_id' => $user->id,
        ]);

        $this->post('/api/message', [
            'recipient' => 'Mike',
            'message' => 'Test message',
        ])->assertStatus(200);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $user->id,
        ]);
    }
}
