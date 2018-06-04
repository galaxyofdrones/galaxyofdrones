<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Researchable as ResearchableContract;
use Koodilab\Contracts\Models\Behaviors\Translatable as TranslatableContract;

/**
 * Unit.
 *
 * @property int                                                   $id
 * @property array                                                 $name
 * @property int                                                   $type
 * @property bool                                                  $is_unlocked
 * @property int                                                   $speed
 * @property int                                                   $attack
 * @property int                                                   $defense
 * @property int                                                   $supply
 * @property int                                                   $train_cost
 * @property int                                                   $train_time
 * @property array                                                 $description
 * @property int|null                                              $detection
 * @property int|null                                              $capacity
 * @property int|null                                              $research_experience
 * @property int|null                                              $research_cost
 * @property int                                                   $research_time
 * @property int                                                   $sort_order
 * @property \Carbon\Carbon|null                                   $created_at
 * @property \Carbon\Carbon|null                                   $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|Population[] $populations
 * @property \Illuminate\Database\Eloquent\Collection|Research[]   $researches
 * @property \Illuminate\Database\Eloquent\Collection|Training[]   $trainings
 * @property \Illuminate\Database\Eloquent\Collection|User[]       $users
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereAttack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDefense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDetection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereIsUnlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereResearchCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereResearchExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereResearchTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereTrainCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereTrainTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Unit extends Model implements ResearchableContract, TranslatableContract
{
    use Behaviors\Modifiable,
        Behaviors\Sortable,
        Behaviors\Researchable,
        Behaviors\Translatable,
        Queries\FindAllByIds,
        Queries\FindAllByIdsAndType,
        Queries\FindByType,
        Queries\FindResearchByUser,
        Relations\BelongsToManyUser,
        Relations\HasManyPopulation,
        Relations\HasManyTraining;

    /**
     * The transporter type.
     *
     * @var int
     */
    const TYPE_TRANSPORTER = 0;

    /**
     * The scout type.
     *
     * @var int
     */
    const TYPE_SCOUT = 1;

    /**
     * The fighter type.
     *
     * @var int
     */
    const TYPE_FIGHTER = 2;

    /**
     * The heavy fighter type.
     *
     * @var int
     */
    const TYPE_HEAVY_FIGHTER = 3;

    /**
     * The settler type.
     *
     * @var int
     */
    const TYPE_SETTLER = 4;

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
     * Get the speed attribute.
     *
     * @return int
     */
    public function getSpeedAttribute()
    {
        return round(
            $this->attributes['speed'] * config('app.speed')
        );
    }

    /**
     * Get the train time attribute.
     *
     * @return int
     */
    public function getTrainTimeAttribute()
    {
        $trainTime = $this->attributes['train_time'];

        if (! empty($this->modifiers['train_time_bonus'])) {
            $trainTime *= max(0, 1 - $this->modifiers['train_time_bonus']);
        }

        return round(
            $trainTime / config('app.speed')
        );
    }

    /**
     * Get the defense attribute.
     *
     * @return int
     */
    public function getDefenseAttribute()
    {
        $defense = $this->attributes['defense'];

        if ($defense && ! empty($this->modifiers['defense_bonus'])) {
            return round(
                $defense * (1 + $this->modifiers['defense_bonus'])
            );
        }

        return $defense;
    }
}
