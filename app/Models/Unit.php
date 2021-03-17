<?php

namespace App\Models;

use App\Contracts\Models\Behaviors\Researchable as ResearchableContract;
use App\Contracts\Models\Behaviors\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Unit.
 *
 * @property int                                                               $id
 * @property array                                                             $name
 * @property int                                                               $type
 * @property bool                                                              $is_unlocked
 * @property int                                                               $speed
 * @property int                                                               $attack
 * @property int                                                               $defense
 * @property int                                                               $supply
 * @property int                                                               $train_cost
 * @property int                                                               $train_time
 * @property array                                                             $description
 * @property int|null                                                          $detection
 * @property int|null                                                          $capacity
 * @property int                                                               $research_experience
 * @property int                                                               $research_cost
 * @property int                                                               $research_time
 * @property int                                                               $sort_order
 * @property \Illuminate\Support\Carbon|null                                   $created_at
 * @property \Illuminate\Support\Carbon|null                                   $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Population[] $populations
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Research[]   $researches
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Training[]   $trainings
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[]       $users
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereAttack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereDefense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereDetection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereIsUnlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereResearchCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereResearchExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereResearchTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereTrainCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereTrainTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Unit extends Model implements ResearchableContract, TranslatableContract
{
    use HasFactory,
        Behaviors\Modifiable,
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
     * Get the train cost attribute.
     *
     * @return int
     */
    public function getTrainCostAttribute()
    {
        $trainCost = $this->attributes['train_cost'];

        if (! empty($this->modifiers['train_cost_penalty'])) {
            $trainCost *= 1 + $this->modifiers['train_cost_penalty'];
        }

        return $trainCost;
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
