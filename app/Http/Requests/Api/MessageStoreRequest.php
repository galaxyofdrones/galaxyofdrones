<?php

namespace Koodilab\Http\Requests\Api;

use Koodilab\Http\Requests\Request;
use Koodilab\Models\Message;
use Koodilab\Models\User;
use Koodilab\Notifications\MessageSended;
use Koodilab\Rules\Recipient;

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
