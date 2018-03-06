<?php

namespace Koodilab\Game;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Koodilab\Contracts\Models\Behaviors\Researchable as ResearchableContract;
use Koodilab\Jobs\Research as ResearchJob;
use Koodilab\Models\Research;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;

class ResearchManager
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * The bus instance.
     *
     * @var Bus
     */
    protected $bus;

    /**
     * Constructor.
     *
     * @param Auth $auth
     * @param Bus  $bus
     */
    public function __construct(Auth $auth, Bus $bus)
    {
        $this->auth = $auth;
        $this->bus = $bus;
    }

    /**
     * Create.
     *
     * @param ResearchableContract|\Illuminate\Database\Eloquent\Model $researchable
     *
     * @return Research
     */
    public function create(ResearchableContract $researchable)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $user->decrementEnergy($researchable->getResearchCostAttribute());

        $research = new Research([
            'user_id' => $user->id,
            'ended_at' => Carbon::now()->addSeconds($researchable->getResearchTimeAttribute()),
        ]);

        $research->researchable()->associate($researchable);
        $research->save();

        $this->bus->dispatch(
            (new ResearchJob($research->id))->delay($research->remaining)
        );

        return $research;
    }

    /**
     * Finish.
     *
     * @param Research $research
     */
    public function finish(Research $research)
    {
        switch ($research->researchable_type) {
            case Resource::class:
                $this->finishResource($research);
                break;
            case Unit::class:
                $this->finishUnit($research);
                break;
        }

        $research->user->incrementExperience(
            $research->researchable->getResearchExperienceAttribute()
        );

        $research->delete();
    }

    /**
     * Cancel.
     *
     * @param Research $research
     */
    public function cancel(Research $research)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $energy = round(
            $research->remaining / $research->researchable->getResearchTimeAttribute() * $research->researchable->getResearchCostAttribute()
        );

        $user->incrementEnergy($energy);

        $research->delete();
    }

    /**
     * Finish the research of resource.
     *
     * @param Research $research
     */
    protected function finishResource(Research $research)
    {
        $userResource = $research->user->resources()
            ->where('resource_id', $research->researchable_id)
            ->first();

        if (! $userResource) {
            $research->user->resources()->attach($research->researchable_id, [
                'is_researched' => true,
                'quantity' => 0,
            ]);
        } else {
            $userResource->pivot->update([
                'is_researched' => true,
            ]);
        }
    }

    /**
     * Finish the research of unit.
     *
     * @param Research $research
     */
    protected function finishUnit(Research $research)
    {
        $userUnit = $research->user->units()
            ->where('unit_id', $research->researchable_id)
            ->first();

        if (! $userUnit) {
            $research->user->units()->attach($research->researchable_id, [
                'is_researched' => true,
                'quantity' => 0,
            ]);
        } else {
            $userUnit->pivot->update([
                'is_researched' => true,
            ]);
        }
    }
}
