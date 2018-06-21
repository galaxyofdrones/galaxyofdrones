<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Block.
 *
 * @property int                 $id
 * @property int                 $blocked_id
 * @property int                 $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property User                $blocked
 * @property User                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereBlockedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereUserId($value)
 * @mixin \Eloquent
 */
class Block extends Model
{
    use Relations\BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * Get the blocked.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function blocked()
    {
        return $this->belongsTo(User::class, 'blocked_id');
    }
}
