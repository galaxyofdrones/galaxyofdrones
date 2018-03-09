<?php

namespace Koodilab\Game;

use Carbon\Carbon;
use Koodilab\Models\Mission;
use Koodilab\Models\MissionLog;
use Koodilab\Models\User;
use Koodilab\Notifications\MissionLogCreated;
use Koodilab\Support\Util;

class MissionManager
{
    /**
     * Create rand.
     *
     * @param User $user
     *
     * @return Mission
     */
    public function createRand(User $user)
    {
        $mission = Mission::create([
            'user_id' => $user->id,
            'energy' => 0,
            'experience' => 0,
        ]);

        $resources = $user->findMissionResources();

        $resources = $resources->random(
            mt_rand(1, $resources->count())
        );

        $totalFrequency = $resources->sum('frequency');
        $totalCapacity = $user->planets->sum('capacity') * $this->randCapacityMultiplier();

        foreach ($resources as $resource) {
            $quantity = ceil(
                $resource->frequency / $totalFrequency * $totalCapacity
            );

            $energy = round(
                $quantity * $resource->efficiency
            );

            $mission->energy += round(
                $energy * Mission::ENERGY_BONUS
            );

            $mission->experience += round(
                $energy * Mission::EXPERIENCE_BONUS
            );

            $mission->resources()->attach($resource->id, [
                'quantity' => $quantity,
            ]);
        }

        $mission->fill([
            'ended_at' => Carbon::now()->addSeconds(Mission::EXPIRATION_TIME),
        ])->save();

        return $mission;
    }

    /**
     * Create log.
     *
     * @param Mission $mission
     *
     * @return MissionLog
     */
    public function createLog(Mission $mission)
    {
        $missionLog = MissionLog::create([
            'user_id' => $mission->user_id,
            'energy' => $mission->energy,
            'experience' => $mission->experience,
        ]);

        foreach ($mission->resources as $resource) {
            $missionLog->resources()->attach($resource->id, [
                'quantity' => $resource->pivot->quantity,
            ]);
        }

        $missionLog->user->notify(
            new MissionLogCreated($missionLog->id)
        );

        return $missionLog;
    }

    /**
     * Finish.
     *
     * @param Mission $mission
     */
    public function finish(Mission $mission)
    {
        $mission->user->incrementEnergyAndExperience(
            $mission->energy, $mission->experience
        );

        $userResources = $mission->user->resources()
            ->whereIn('resource_id', $mission->resources->modelKeys())
            ->get();

        foreach ($mission->resources as $resource) {
            $userResource = $userResources->firstWhere('id', $resource->id);

            $userResource->pivot->update([
                'quantity' => max(0, $userResource->pivot->quantity - $resource->pivot->quantity),
            ]);
        }

        $this->createLog($mission);

        $mission->delete();
    }

    /**
     * Get a random capacity multiplier.
     *
     * @return float
     */
    protected function randCapacityMultiplier()
    {
        return Mission::MIN_CAPACITY + (Mission::MAX_CAPACITY - Mission::MIN_CAPACITY) * Util::randFloat();
    }
}
