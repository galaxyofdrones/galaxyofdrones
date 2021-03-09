<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bookmark.
 *
 * @property int                             $id
 * @property int                             $star_id
 * @property int                             $user_id
 * @property string                          $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Star                $star
 * @property \App\Models\User                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bookmark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bookmark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bookmark query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bookmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bookmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bookmark whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bookmark whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bookmark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bookmark whereUserId($value)
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
