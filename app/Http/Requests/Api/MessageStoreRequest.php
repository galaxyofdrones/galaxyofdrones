<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageSended;
use App\Rules\Recipient;

class MessageStoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @throws \Exception|\Throwable
     *
     * @return array
     */
    public function rules()
    {
        return [
            'recipient' => [
                'required',
                $this->container->make(Recipient::class),
            ],
            'message' => 'required',
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $recipient = User::findByUsername(
            $this->get('recipient')
        );

        $message = Message::create([
            'sender_id' => $this->user()->id,
            'recipient_id' => $recipient->id,
            'message' => $this->get('message'),
        ]);

        $recipient->notify(
            new MessageSended($message->id)
        );
    }
}
