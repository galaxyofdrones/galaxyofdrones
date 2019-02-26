<?php

namespace Koodilab\Observers;

use Illuminate\Contracts\Events\Dispatcher as Event;
use Koodilab\Events\PlanetUpdated;
use Koodilab\Game\StateManager;
use Koodilab\Models\Planet;
use Koodilab\Models\User;

class PlanetObserver
{
    /**
     * The event instance.
     *
     * @var Event
     */
    protected $event;

    /**
     * The state manager instance.
     *
     * @var StateManager
     */
    protected $stateManager;

    /**
     * Constructor.
     *
     * @param Event        $event
     * @param StateManager $stateManager
     */
    public function __construct(Event $event, StateManager $stateManager)
    {
        $this->event = $event;
        $this->stateManager = $stateManager;
    }

    /**
     * Updating.
     *
     * @param Planet $planet
     */
    public function updating(Planet $planet)
    {
        if ($planet->isDirty('user_id')) {
            $userId = $planet->getOriginal('user_id');

            if ($userId) {
                $user = User::find($userId);

                $planet->custom_name = null;
                $planet->capacity = null;
                $planet->supply = null;
                $planet->mining_rate = null;
                $planet->production_rate = null;
                $planet->defense_bonus = null;
                $planet->construction_time_bonus = null;
                $planet->shield()->delete();
                $planet->incomingMovements()->where('user_id', $user->id)->delete();
                $planet->outgoingMovements()->where('user_id', $user->id)->delete();
                $planet->constructions()->delete();
                $planet->upgrades()->delete();
                $planet->trainings()->delete();

                $planet->grids()->update([
                    'level' => null,
                    'building_id' => null,
                ]);

                if ($planet->id == $user->current_id) {
                    $user->update([
                        'current_id' => $user->capital_id,
                    ]);
                }

                $this->stateManager->syncUser($user);
            }
        }

        if ($planet->user_id) {
            $this->stateManager->syncUser($planet->user);
        }
    }

    /**
     * Updated.
     *
     * @param Planet $planet
     */
    public function updated(Planet $planet)
    {
        $this->event->dispatch(
            new PlanetUpdated($planet->id)
        );
    }
}
