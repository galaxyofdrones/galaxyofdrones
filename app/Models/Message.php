<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Message.
 *
 * @property int                             $id
 * @property int                             $sender_id
 * @property int                             $recipient_id
 * @property string                          $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Koodilab\Models\User           $recipient
 * @property \Koodilab\Models\User           $sender
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Message whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * Get the sender.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
