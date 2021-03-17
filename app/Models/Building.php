<?php

namespace App\Models;

use App\Contracts\Models\Behaviors\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NestedSet;

/**
 * Building.
 *
 * @property int                                                                 $id
 * @property int                                                                 $_lft
 * @property int                                                                 $_rgt
 * @property int|null                                                            $parent_id
 * @property array                                                               $name
 * @property int                                                                 $type
 * @property int                                                                 $end_level
 * @property int                                                                 $construction_experience
 * @property int                                                                 $construction_cost
 * @property int                                                                 $construction_time
 * @property array                                                               $description
 * @property int|null                                                            $limit
 * @property int                                                                 $defense
 * @property int                                                                 $detection
 * @property int                                                                 $capacity
 * @property int                                                                 $supply
 * @property int                                                                 $mining_rate
 * @property int                                                                 $production_rate
 * @property float                                                               $defense_bonus
 * @property float                                                               $construction_time_bonus
 * @property float                                                               $trade_time_bonus
 * @property float                                                               $train_time_bonus
 * @property \Illuminate\Support\Carbon|null                                     $created_at
 * @property \Illuminate\Support\Carbon|null                                     $updated_at
 * @property \Kalnoy\Nestedset\Collection|\App\Models\Building[]                 $children
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Construction[] $constructions
 * @property int                                                                 $level
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Grid[]         $grids
 * @property \App\Models\Building|null                                           $parent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building d()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereConstructionCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereConstructionExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereConstructionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereConstructionTimeBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereDefense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereDefenseBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereDetection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereEndLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereMiningRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereProductionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereTradeTimeBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereTrainTimeBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Building extends Model implements TranslatableContract
{
    use HasFactory,
        Behaviors\Categorizable,
        Behaviors\Modifiable,
        Behaviors\Translatable,
        Concerns\HasLevel,
        Queries\FindByType,
        Relations\HasManyConstruction,
        Relations\HasManyGrid;

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
     * Get the construction experience attribute.
     *
     * @return int
     */
    public function getConstructionExperienceAttribute()
    {
        $constructionExperience = $this->attributes['construction_experience'];

        if ($this->hasLowerLevel()) {
            return round(
                $this->applyLinearForumla($constructionExperience)
            );
        }

        return $constructionExperience;
    }

    /**
     * Get the construction cost attribute.
     *
     * @return int
     */
    public function getConstructionCostAttribute()
    {
        $constructionCost = $this->attributes['construction_cost'];

        if ($this->hasLowerLevel()) {
            $constructionCost = round(
                $this->applyExpFormula($constructionCost)
            );
        }

        if (! empty($this->modifiers['construction_cost_penalty'])) {
            $constructionCost *= 1 + $this->modifiers['construction_cost_penalty'];
        }

        return $constructionCost;
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

        if (! empty($this->modifiers['construction_time_bonus'])) {
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
        $defense = $this->attributes['defense'];

        if ($this->type == static::TYPE_DEFENSIVE && $defense) {
            if ($this->hasLowerLevel()) {
                $defense = $this->applyLinearForumla($defense);
            }

            if (! empty($this->modifiers['defense_bonus'])) {
                $defense *= 1 + $this->modifiers['defense_bonus'];
            }

            return round($defense);
        }

        return $defense;
    }

    /**
     * Get the detection attribute.
     *
     * @return int
     */
    public function getDetectionAttribute()
    {
        $detection = $this->attributes['detection'];

        if ($this->type == static::TYPE_SCOUT && $detection && $this->hasLowerLevel()) {
            return round(
                $this->applyLinearForumla($detection)
            );
        }

        return $detection;
    }

    /**
     * Get the capacity attribute.
     *
     * @return int
     */
    public function getCapacityAttribute()
    {
        $capacity = $this->attributes['capacity'];

        if ($this->type == static::TYPE_CONTAINER && $capacity && $this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($capacity)
            );
        }

        return $capacity;
    }

    /**
     * Get the supply attribute.
     *
     * @return int
     */
    public function getSupplyAttribute()
    {
        $supply = $this->attributes['supply'];

        if ($this->type == static::TYPE_CONTAINER && $supply && $this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($supply)
            );
        }

        return $supply;
    }

    /**
     * Get the mining rate attribute.
     *
     * @return int
     */
    public function getMiningRateAttribute()
    {
        $miningRate = $this->attributes['mining_rate'];

        if ($this->type == static::TYPE_MINER && $miningRate) {
            if ($this->hasLowerLevel()) {
                return round(
                    $this->applyLinearForumla($miningRate) * config('app.speed')
                );
            }

            return $miningRate * config('app.speed');
        }

        return $miningRate;
    }

    /**
     * Get the production rate attribute.
     *
     * @return int
     */
    public function getProductionRateAttribute()
    {
        $productionRate = $this->attributes['production_rate'];

        if (in_array($this->type, [static::TYPE_CENTRAL, static::TYPE_PRODUCER]) && $productionRate) {
            if ($this->hasLowerLevel()) {
                return round(
                    $this->applyLinearForumla($productionRate) * config('app.speed')
                );
            }

            return $productionRate * config('app.speed');
        }

        return $productionRate;
    }

    /**
     * Get the defense bonus attribute.
     *
     * @return float
     */
    public function getDefenseBonusAttribute()
    {
        $defenseBonus = $this->attributes['defense_bonus'];

        if ($this->type == static::TYPE_DEFENSIVE && $defenseBonus && $this->hasLowerLevel()) {
            return round(
                $this->applyLinearForumla($defenseBonus),
                2
            );
        }

        return $defenseBonus;
    }

    /**
     * Get the construction time bonus attribute.
     *
     * @return float
     */
    public function getConstructionTimeBonusAttribute()
    {
        $constructionTimeBonus = $this->attributes['construction_time_bonus'];

        if ($this->type == static::TYPE_CENTRAL && $constructionTimeBonus && $this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($constructionTimeBonus),
                2
            );
        }

        return $constructionTimeBonus;
    }

    /**
     * Get the trade time bonus attribute.
     *
     * @return float
     */
    public function getTradeTimeBonusAttribute()
    {
        $tradeTimeBonus = $this->attributes['trade_time_bonus'];

        if ($this->type == static::TYPE_TRADER && $tradeTimeBonus && $this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($tradeTimeBonus),
                2
            );
        }

        return $tradeTimeBonus;
    }

    /**
     * Get the train time bonus attribute.
     *
     * @return float
     */
    public function getTrainTimeBonusAttribute()
    {
        $trainTimeBonus = $this->attributes['train_time_bonus'];

        if ($this->type == static::TYPE_TRAINER && $trainTimeBonus && $this->hasLowerLevel()) {
            return round(
                $this->applyExpFormula($trainTimeBonus),
                2
            );
        }

        return $trainTimeBonus;
    }

    /**
     * {@inheritdoc}
     */
    protected function validateModifiers(array $modifiers)
    {
        if (! empty($modifiers['level'])) {
            return $this->hasLevel($modifiers['level']);
        }

        return true;
    }
}
