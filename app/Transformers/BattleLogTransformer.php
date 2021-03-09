<?php

namespace App\Transformers;

use App\Models\BattleLog;
use App\Models\Building;
use App\Models\Resource;
use App\Models\Unit;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Translation\Translator;

class BattleLogTransformer extends Transformer
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * The translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Constructor.
     */
    public function __construct(Auth $auth, Translator $translator)
    {
        $this->auth = $auth;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     *
     * @param BattleLog $item
     */
    public function transform($item)
    {
        /** @var \App\Models\User $user */
        $user = $this->auth->guard()->user();

        return [
            'id' => $item->id,
            'type' => $item->type,
            'winner' => $item->winner,
            'created_at' => $item->created_at->toDateTimeString(),
            'is_attack' => $item->attacker_id == $user->id,
            'is_defense' => $item->defender_id == $user->id,
            'start' => [
                'id' => $item->start_id,
                'resource_id' => $item->start->resource_id,
                'name' => $item->start_name,
            ],
            'end' => [
                'id' => $item->end_id,
                'resource_id' => $item->end->resource_id,
                'name' => $item->end_name,
            ],
            'attacker' => [
                'id' => $item->attacker_id,
                'username' => $item->attacker->username,
            ],
            'defender' => [
                'id' => $item->defender_id,
                'username' => $item->defender_id
                    ? $item->defender->username
                    : $this->translator->get('messages.free'),
            ],
            'resources' => $this->resources($item),
            'buildings' => $this->buildings($item),
            'attacker_units' => $this->attackerUnits($item),
            'defender_units' => $this->defenderUnits($item),
        ];
    }

    /**
     * Get the resources.
     *
     * @return array
     */
    protected function resources(BattleLog $battleLog)
    {
        return $battleLog->resources->transform(function (Resource $resource) {
            return [
                'id' => $resource->id,
                'name' => $resource->translation('name'),
                'description' => $resource->translation('description'),
                'quantity' => $resource->pivot->quantity,
                'losses' => $resource->pivot->losses,
            ];
        });
    }

    /**
     * Get the buildings.
     *
     * @return array
     */
    protected function buildings(BattleLog $battleLog)
    {
        return $battleLog->buildings->transform(function (Building $building) {
            return [
                'id' => $building->id,
                'name' => $building->translation('name'),
                'description' => $building->translation('description'),
                'level' => $building->pivot->level,
                'losses' => $building->pivot->losses,
            ];
        });
    }

    /**
     * Get the attacker units.
     *
     * @return array
     */
    protected function attackerUnits(BattleLog $battleLog)
    {
        return $battleLog->attackerUnits->transform(function (Unit $unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->translation('name'),
                'description' => $unit->translation('description'),
                'quantity' => $unit->pivot->quantity,
                'losses' => $unit->pivot->losses,
            ];
        });
    }

    /**
     * Get the defender units.
     *
     * @return array
     */
    protected function defenderUnits(BattleLog $battleLog)
    {
        return $battleLog->defenderUnits->transform(function (Unit $unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->translation('name'),
                'description' => $unit->translation('description'),
                'quantity' => $unit->pivot->quantity,
                'losses' => $unit->pivot->losses,
            ];
        });
    }
}
