<?php

namespace Koodilab\Models\Transformers;

use Koodilab\Models\Planet;
use Koodilab\Models\User;

class UserCapitalTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param User $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'capital_id' => $item->capital_id,
            'capital_change_remaining' => $item->capital_change_remaining,
            'incoming_capital_movement_count' => $item->capital->incomingCapitalMovementCount(),
            'planets' => $this->planets($item),
        ];
    }

    /**
     * Get the planets.
     *
     * @param User $user
     *
     * @return array
     */
    protected function planets(User $user)
    {
        return $user->findPlanetsOrderByName()
            ->transform(function (Planet $planet) {
                return [
                    'id' => $planet->id,
                    'name' => $planet->display_name,
                ];
            });
    }
}
