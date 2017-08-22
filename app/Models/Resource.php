<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Translatable as TranslatableContract;
use Koodilab\Models\Behaviors\Translatable;
use Koodilab\Models\Relations\BelongsToManyUser;
use Koodilab\Models\Relations\HasManyPlanet;
use Koodilab\Models\Relations\HasManyStock;
use Koodilab\Models\Relations\MorphManyResearch;

/**
 * Resource.
 */
class Resource extends Model implements TranslatableContract
{
    use Translatable, BelongsToManyUser, HasManyPlanet, HasManyStock, MorphManyResearch;

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
        'description' => 'json',
    ];

    /**
     * Get the research time attribute.
     *
     * @return int
     */
    public function getResearchTimeAttribute()
    {
        return round($this->attributes['research_time'] / config('app.speed'));
    }
}
