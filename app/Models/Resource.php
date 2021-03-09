<?php

namespace App\Models;

use App\Contracts\Models\Behaviors\Researchable as ResearchableContract;
use App\Contracts\Models\Behaviors\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Resource.
 *
 * @property int                                                             $id
 * @property array                                                           $name
 * @property bool                                                            $is_unlocked
 * @property float                                                           $frequency
 * @property float                                                           $efficiency
 * @property array                                                           $description
 * @property int                                                             $research_experience
 * @property int                                                             $research_cost
 * @property int                                                             $research_time
 * @property int                                                             $sort_order
 * @property \Illuminate\Support\Carbon|null                                 $created_at
 * @property \Illuminate\Support\Carbon|null                                 $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Planet[]   $planets
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Research[] $researches
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Stock[]    $stocks
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[]     $users
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereEfficiency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereIsUnlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereResearchCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereResearchExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereResearchTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Resource extends Model implements ResearchableContract, TranslatableContract
{
    use Behaviors\Researchable,
        Behaviors\Sortable,
        Behaviors\Translatable,
        Queries\FindAllByIds,
        Queries\FindResearchByUser,
        Relations\HasManyPlanet,
        Relations\HasManyStock;

    /**
     * {@inheritdoc}
     */
    protected $attributes = [
        'name' => '{}',
        'is_unlocked' => false,
        'description' => '{}',
    ];

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => 'json',
        'is_unlocked' => 'bool',
        'description' => 'json',
    ];

    /**
     * Get the users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_researched', 'quantity')
            ->withTimestamps();
    }
}
