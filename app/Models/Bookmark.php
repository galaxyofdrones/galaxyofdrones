<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Models\Relations\BelongsToStar;
use Koodilab\Models\Relations\BelongsToUser;

/**
 * Bookmark.
 *
 * @property int $id
 * @property int $star_id
 * @property int $user_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Star $star
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Bookmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookmark whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookmark whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookmark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookmark whereUserId($value)
 * @mixin \Eloquent
 */
class Bookmark extends Model
{
    use BelongsToStar, BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $perPage = 30;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];
}
