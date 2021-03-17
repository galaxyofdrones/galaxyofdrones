<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Research.
 *
 * @property int                                                             $id
 * @property int                                                             $user_id
 * @property string                                                          $researchable_type
 * @property int                                                             $researchable_id
 * @property \Illuminate\Support\Carbon                                      $ended_at
 * @property \Illuminate\Support\Carbon|null                                 $created_at
 * @property \Illuminate\Support\Carbon|null                                 $updated_at
 * @property int                                                             $remaining
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Research[] $researchable
 * @property \App\Models\User                                                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research whereResearchableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research whereResearchableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Research whereUserId($value)
 * @mixin \Eloquent
 */
class Research extends Model
{
    use HasFactory,
        Behaviors\Timeable,
        Relations\BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'ended_at',
    ];

    /**
     * Get the researchable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function researchable()
    {
        return $this->morphTo();
    }
}
