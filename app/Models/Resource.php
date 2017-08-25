<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Translatable as TranslatableContract;
use Koodilab\Models\Behaviors\Researchable;
use Koodilab\Models\Behaviors\Translatable;
use Koodilab\Models\Relations\BelongsToManyUser;
use Koodilab\Models\Relations\HasManyPlanet;
use Koodilab\Models\Relations\HasManyStock;

/**
 * Resource.
 *
 * @property int $id
 * @property array $name
 * @property bool $is_unlocked
 * @property float $frequency
 * @property float $efficiency
 * @property array $description
 * @property int|null $research_experience
 * @property int|null $research_cost
 * @property int $research_time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Planet[] $planets
 * @property-read \Illuminate\Database\Eloquent\Collection|Research[] $researches
 * @property-read \Illuminate\Database\Eloquent\Collection|Stock[] $stocks
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users
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
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Resource extends Model implements TranslatableContract
{
    use Researchable, Translatable, BelongsToManyUser, HasManyPlanet, HasManyStock;

    /**
     * {@inheritdoc}
     */
    protected $perPage = 30;

    /**
     * {@inheritdoc}
     */
    protected $attributes = [
        'name' => '{}',
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
}
