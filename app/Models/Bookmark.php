<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bookmark.
 *
 * @property int                 $id
 * @property int                 $star_id
 * @property int                 $user_id
 * @property string              $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property Star                $star
 * @property User                $user
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
    use Queries\FindByStarAndUser,
        Relations\BelongsToStar,
        Relations\BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];
}
