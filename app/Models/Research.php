<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Jobs\Research as ResearchJob;

/**
 * Research.
 *
 * @property int $id
 * @property int $user_id
 * @property int $researchable_id
 * @property string $researchable_type
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $remaining
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $researchable
 * @property User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereResearchableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereResearchableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereUserId($value)
 * @mixin \Eloquent
 */
class Research extends Model implements TimeableContract
{
    use Behaviors\Timeable,
        Relations\BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $perPage = 30;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'ended_at',
    ];

    /**
     * Create from.
     *
     * @param Model $researchable
     *
     * @return static
     */
    public static function createFrom(Model $researchable)
    {
        /** @var User $user */
        $user = auth()->user();

        $user->decrementEnergy($researchable->research_cost);

        $model = new static([
            'user_id' => $user->id,
            'ended_at' => Carbon::now()->addSeconds($researchable->research_time),
        ]);

        $model->researchable()->associate($researchable);
        $model->save();

        dispatch(
            (new ResearchJob($model->id))->delay($model->remaining)
        );

        return $model;
    }

    /**
     * Get the researchable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function researchable()
    {
        return $this->morphTo();
    }

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        switch ($this->researchable_type) {
            case Resource::class:
                $this->user->resources()->attach($this->researchable_id, [
                    'quantity' => 0,
                ]);
                break;
            case Unit::class:
                $this->user->units()->attach($this->researchable_id);
                break;
        }

        $this->user->incrementExperience(
            $this->researchable->research_experience
        );

        $this->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        /** @var User $user */
        $user = auth()->user();

        $user->incrementEnergy(round(
            $this->remaining / $this->researchable->research_time * $this->researchable->research_cost
        ));

        $this->delete();
    }
}
