<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Researchable as ResearchableContract;
use Koodilab\Contracts\Models\Behaviors\Translatable as TranslatableContract;

/**
 * Resource.
 *
 * @property int                                                                  $id
 * @property array                                                                $name
 * @property bool                                                                 $is_unlocked
 * @property float                                                                $frequency
 * @property float                                                                $efficiency
 * @property array                                                                $description
 * @property int                                                                  $research_experience
 * @property int                                                                  $research_cost
 * @property int                                                                  $research_time
 * @property int                                                                  $sort_order
 * @property \Illuminate\Support\Carbon|null                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                      $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Planet[]   $planets
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Research[] $researches
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\Stock[]    $stocks
 * @property \Illuminate\Database\Eloquent\Collection|\Koodilab\Models\User[]     $users
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereEfficiency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereIsUnlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereResearchCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereResearchExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereResearchTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Koodilab\Models\Resource whereUpdatedAt($value)
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
