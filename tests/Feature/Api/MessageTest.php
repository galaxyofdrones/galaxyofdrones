<?php

namespace Tests\Feature\Api;

use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageSended;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'started_at' => Carbon::now(),
        ]);

        Passport::actingAs($user);

        $sender = User::factory()->create([
            'username' => 'Mike',
            'started_at' => Carbon::now(),
        ]);

        $message = Message::factory()->create([
            'recipient_id' => $user->id,
            'sender_id' => $sender->id,
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
                            'is_blocked',
                            'is_blocked_by',
                        ],
                    ],
                ],
            ])->assertJson([
                'data' => [
                    [
                        'id' => $message->id,
                        'message' => nl2br(e($message->message)),
                        'created_at' => $message->created_at->toDateTimeString(),
                        'sender' => [
                            'id' => $message->sender->id,
                            'username' => $message->sender->username,
                            'is_blocked' => false,
                            'is_blocked_by' => false,
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
