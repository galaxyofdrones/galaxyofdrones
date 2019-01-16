<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Rank.
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property int                             $mission_count
 * @property int                             $expedition_count
 * @property int                             $planet_count
 * @property int                             $winning_battle_count
 * @property int                             $losing_battle_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property User                            $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Rank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rank query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rank whereExpeditionCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rank whereLosingBattleCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rank whereMissionCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rank wherePlanetCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rank whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rank whereWinningBattleCount($value)
 * @mixin \Eloquent
 */
class Rank extends Model
{
    use Queries\PaginatePveUsers,
        Queries\PaginatePvpUsers,
        Relations\BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];
}
