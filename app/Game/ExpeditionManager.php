<?php

namespace Koodilab\Game;

use Carbon\Carbon;
use Koodilab\Models\Expedition;
use Koodilab\Models\ExpeditionLog;
use Koodilab\Models\Unit;
use Koodilab\Models\User;
use Koodilab\Notifications\ExpeditionLogCreated;
use Koodilab\Support\Util;

class ExpeditionManager
{
    /**
     * Create rand.
     *
     * @param User $user
     *
     * @return Expedition
     */
    public function createRand(User $user)
    {
        $star = $user->findExpeditionStar();

        if (! $star) {
            return null;
        }

        $scoutUnit = $user->units()
            ->where('is_researched', true)
            ->where('type', Unit::TYPE_SCOUT)
            ->first();

        if (! $scoutUnit) {
            return null;
        }

        $expedition = Expedition::create([
            'star_id' => $star->id,
            'user_id' => $user->id,
            'solarion' => Expedition::SOLARION_COUNT,
            'experience' => 0,
        ]);

        $totalSupply = $user->planets->sum('supply') * $this->randSupplyMultiplier();

        $quantity = ceil(
            $totalSupply / $scoutUnit->supply
        );

        $expedition->units()->attach($scoutUnit->id, [
            'quantity' => $quantity,
        ]);

        $expedition->fill([
            'experience' => round($quantity * $scoutUnit->train_cost * Expedition::EXPERIENCE_BONUS),
            'ended_at' => Carbon::now()->addSeconds(Expedition::EXPIRATION_TIME),
        ])->save();

        return $expedition;
    }

    /**
     * Create log.
     *
     * @param Expedition $expedition
     *
     * @return ExpeditionLog
     */
    public function createLog(Expedition $expedition)
    {
        $expeditionLog = ExpeditionLog::create([
            'star_id' => $expedition->star_id,
            'user_id' => $expedition->user_id,
            'solarion' => $expedition->solarion,
            'experience' => $expedition->experience,
        ]);

        foreach ($expedition->units as $unit) {
            $expeditionLog->units()->attach($unit->id, [
                'quantity' => $unit->pivot->quantity,
            ]);
        }

        $expeditionLog->user->notify(
            new ExpeditionLogCreated($expeditionLog->id)
        );

        return $expeditionLog;
    }

    /**
     * Finish.
     *
     * @param Expedition $expedition
     */
    public function finish(Expedition $expedition)
    {
        $expedition->user->incrementSolarionAndExperience(
            $expedition->solarion, $expedition->experience
        );

        $userUnits = $expedition->user->units()
            ->whereIn('unit_id', $expedition->units->modelKeys())
            ->get();

        foreach ($expedition->units as $unit) {
            $userUnit = $userUnits->firstWhere('id', $unit->id);

            $userUnit->pivot->update([
                'quantity' => max(0, $userUnit->pivot->quantity - $unit->pivot->quantity),
            ]);
        }

        $this->createLog($expedition);

        $expedition->delete();
    }

    /**
     * Get a random supply multiplier.
     *
     * @return float
     */
    protected function randSupplyMultiplier()
    {
        return Expedition::MIN_SUPPLY + (Expedition::MAX_SUPPLY - Expedition::MIN_SUPPLY) * Util::randFloat();
    }
}
