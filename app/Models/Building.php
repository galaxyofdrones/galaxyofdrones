<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NestedSet;
use Koodilab\Contracts\Models\Behaviors\Translatable as TranslatableContract;
use Koodilab\Models\Behaviors\Categorizable;
use Koodilab\Models\Behaviors\Modifiable;
use Koodilab\Models\Behaviors\Translatable;
use Koodilab\Models\Relations\HasManyConstruction;
use Koodilab\Models\Relations\HasManyGrid;

/**
 * Building.
 *
 * @property int $id
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property array $name
 * @property int $type
 * @property int $end_level
 * @property int $construction_experience
 * @property int $construction_cost
 * @property int $construction_time
 * @property array $description
 * @property int|null $limit
 * @property int $defense
 * @property int $detection
 * @property int $capacity
 * @property int $supply
 * @property int $mining_rate
 * @property int $production_rate
 * @property int $mission_time
 * @property float $defense_bonus
 * @property float $construction_time_bonus
 * @property float $train_time_bonus
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Kalnoy\Nestedset\Collection|Building[] $children
 * @property-read \Illuminate\Database\Eloquent\Collection|Construction[] $constructions
 * @property-read int $level
 * @property-read \Illuminate\Database\Eloquent\Collection|Grid[] $grids
 * @property-read Building|null $parent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereConstructionCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereConstructionExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereConstructionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereConstructionTimeBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereDefense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereDefenseBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereDetection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereEndLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereMiningRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereMissionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereProductionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereTrainTimeBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Building extends Model implements TranslatableContract
{
    use Categorizable, Modifiable, Translatable, HasManyConstruction, HasManyGrid;

    /**
     * The central type.
     *
     * @var int
     */
    const TYPE_CENTRAL = 0;

    /**
     * The miner type.
     *
     * @var int
     */
    const TYPE_MINER = 1;

    /**
     * The producer type.
     *
     * @var int
     */
    const TYPE_PRODUCER = 2;

    /**
     * The container type.
     *
     * @var int
     */
    const TYPE_CONTAINER = 3;

    /**
     * The trader type.
     *
     * @var int
     */
    const TYPE_TRADER = 4;

    /**
     * The trainer type.
     *
     * @var int
     */
    const TYPE_TRAINER = 5;

    /**
     * The scout type.
     *
     * @var int
     */
    const TYPE_SCOUT = 6;

    /**
     * The defensive type.
     *
     * @var int
     */
    const TYPE_DEFENSIVE = 7;

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
        'id', NestedSet::LFT, NestedSet::RGT, 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => 'json',
        'description' => 'json',
    ];

    /**
     * Has the level?
     *
     * @param int $level
     *
     * @return bool
     */
    public function hasLevel($level)
    {
        return $level > 0 && $level <= $this->end_level;
    }

    /**
     * Has lower level?
     *
     * @return bool
     */
    public function hasLowerLevel()
    {
        return $this->hasLowerLevel();
    }

    /**
     * Get the level attribute.
     *
     * @return int
     */
    public function getLevelAttribute()
    {
        if (!empty($this->modifiers['level'])) {
            return $this->modifiers['level'];
        }

        if (!empty($this->attributes['level'])) {
            return $this->attributes['level'];
        }

        return $this->end_level;
    }

    /**
     * Get the construction experience attribute.
     *
     * @return int
     */
    public function getConstructionExperienceAttribute()
    {
        if ($this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($this->attributes['construction_experience'])
            );
        }

        return $this->attributes['construction_experience'];
    }

    /**
     * Get the construction cost attribute.
     *
     * @return int
     */
    public function getConstructionCostAttribute()
    {
        if ($this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($this->attributes['construction_cost'])
            );
        }

        return $this->attributes['construction_cost'];
    }

    /**
     * Get the construction time attribute.
     *
     * @return int
     */
    public function getConstructionTimeAttribute()
    {
        $constructionTime = $this->attributes['construction_time'];

        if ($this->hasLowerLevel()) {
            $constructionTime = $this->applyExpFormula($constructionTime, 3);
        }

        if (!empty($this->modifiers['construction_time_bonus'])) {
            $constructionTime *= max(0, 1 - $this->modifiers['construction_time_bonus']);
        }

        return round(
            $constructionTime / config('app.speed')
        );
    }

    /**
     * Get the defense attribute.
     *
     * @return int
     */
    public function getDefenseAttribute()
    {
        if ($this->type == static::TYPE_DEFENSIVE && $this->attributes['defense']) {
            $defense = $this->attributes['defense'];

            if ($this->hasLowerLevel()) {
                $defense = $this->applyLinearForumla($defense);
            }

            if (!empty($this->modifiers['defense_bonus'])) {
                $defense *= 1 + $this->modifiers['defense_bonus'];
            }

            return round($defense);
        }

        return $this->attributes['defense'];
    }

    /**
     * Get the detection attribute.
     *
     * @return int
     */
    public function getDetectionAttribute()
    {
        if ($this->type == static::TYPE_SCOUT && $this->attributes['detection'] && $this->hasLowerLevel()) {
            return round(
                $this->applyLinearForumla($this->attributes['detection'])
            );
        }

        return $this->attributes['detection'];
    }

    /**
     * Get the capacity attribute.
     *
     * @return int
     */
    public function getCapacityAttribute()
    {
        if ($this->type == static::TYPE_CONTAINER && $this->attributes['capacity'] && $this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($this->attributes['capacity'])
            );
        }

        return $this->attributes['capacity'];
    }

    /**
     * Get the supply attribute.
     *
     * @return int
     */
    public function getSupplyAttribute()
    {
        if ($this->type == static::TYPE_CONTAINER && $this->attributes['supply'] && $this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($this->attributes['supply'])
            );
        }

        return $this->attributes['supply'];
    }

    /**
     * Get the mining rate attribute.
     *
     * @return int
     */
    public function getMiningRateAttribute()
    {
        if ($this->type == static::TYPE_MINER && $this->attributes['mining_rate']) {
            if ($this->hasLowerLevel()) {
                return round(
                    $this->applyLinearForumla($this->attributes['mining_rate']) * config('app.speed')
                );
            }

            return $this->attributes['mining_rate'] * config('app.speed');
        }

        return $this->attributes['mining_rate'];
    }

    /**
     * Get the production rate attribute.
     *
     * @return int
     */
    public function getProductionRateAttribute()
    {
        if ($this->type == static::TYPE_PRODUCER && $this->attributes['production_rate']) {
            if ($this->hasLowerLevel()) {
                return round(
                    $this->applyLinearForumla($this->attributes['production_rate']) * config('app.speed')
                );
            }

            return $this->attributes['production_rate'] * config('app.speed');
        }

        return $this->attributes['production_rate'];
    }

    /**
     * Get the mission time attribute.
     *
     * @return int
     */
    public function getMissionTimeAttribute()
    {
        if ($this->type == static::TYPE_TRADER && $this->attributes['mission_time'] && $this->hasLowerLevel()) {
            return round(
                $this->applyLinearForumla($this->attributes['mission_time'])
            );
        }

        return $this->attributes['mission_time'];
    }

    /**
     * Get the defense bonus attribute.
     *
     * @return float
     */
    public function getDefenseBonusAttribute()
    {
        if ($this->type == static::TYPE_DEFENSIVE && $this->attributes['defense_bonus'] && $this->hasLowerLevel()) {
            return round(
                $this->applyLinearForumla($this->attributes['defense_bonus']), 2
            );
        }

        return $this->attributes['defense_bonus'];
    }

    /**
     * Get the construction time bonus attribute.
     *
     * @return float
     */
    public function getConstructionTimeBonusAttribute()
    {
        if ($this->type == static::TYPE_CENTRAL && $this->attributes['construction_time_bonus'] && $this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($this->attributes['construction_time_bonus']), 2
            );
        }

        return $this->attributes['construction_time_bonus'];
    }

    /**
     * Get the train time bonus attribute.
     *
     * @return float
     */
    public function getTrainTimeBonusAttribute()
    {
        if ($this->type == static::TYPE_TRAINER && $this->attributes['train_time_bonus'] && $this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($this->attributes['train_time_bonus']), 2
            );
        }

        return $this->attributes['train_time_bonus'];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateModifiers(array $modifiers)
    {
        if (!empty($modifiers['level'])) {
            return $this->hasLevel($modifiers['level']);
        }

        return true;
    }

    /**
     * Apply the linear formula.
     *
     * @param mixed $value
     *
     * @return float
     */
    protected function applyLinearForumla($value)
    {
        return $value * ($this->level / $this->end_level);
    }

    /**
     * Apply the exp forumla.
     *
     * @param mixed $value
     * @param int   $exp
     *
     * @return float
     */
    protected function applyExpFormula($value, $exp = 2)
    {
        return $value * pow($this->level / $this->end_level, $exp);
    }
}
