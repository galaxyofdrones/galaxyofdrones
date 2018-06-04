<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Researchable as ResearchableContract;
use Koodilab\Contracts\Models\Behaviors\Translatable as TranslatableContract;

/**
 * Resource.
 *
 * @property int                                                 $id
 * @property array                                               $name
 * @property bool                                                $is_unlocked
 * @property float                                               $frequency
 * @property float                                               $efficiency
 * @property array                                               $description
 * @property int|null                                            $research_experience
 * @property int|null                                            $research_cost
 * @property int                                                 $research_time
 * @property int                                                 $sort_order
 * @property \Carbon\Carbon|null                                 $created_at
 * @property \Carbon\Carbon|null                                 $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|Planet[]   $planets
 * @property \Illuminate\Database\Eloquent\Collection|Research[] $researches
 * @property \Illuminate\Database\Eloquent\Collection|Stock[]    $stocks
 * @property \Illuminate\Database\Eloquent\Collection|User[]     $users
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereEfficiency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereIsUnlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereResearchCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereResearchExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereResearchTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereUpdatedAt($value)
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
