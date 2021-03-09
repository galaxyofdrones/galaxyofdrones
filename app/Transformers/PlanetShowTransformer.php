<?php

namespace App\Transformers;

use App\Models\Planet;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Translation\Translator;

class PlanetShowTransformer extends Transformer
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
     * @param \App\Models\Planet $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'resource_id' => $item->resource_id,
            'user_id' => $item->user_id,
            'resource_count' => $item->resource_count,
            'username' => $item->user_id
                ? $item->user->username
                : $this->translator->get('messages.free'),
            'can_occupy' => $this->canOccupy($item),
            'has_shield' => $item->hasShield(),
            'travel_time' => $this->travelTime($item),
        ];
    }

    /**
     * Can occupy?
     *
     * @return bool
     */
    protected function canOccupy(Planet $planet)
    {
        $user = $this->user();

        if ($user) {
            return $user->canOccupy($planet);
        }

        return false;
    }

    /**
     * Get the travel from current planet.
     *
     * @return int
     */
    protected function travelTime(Planet $planet)
    {
        $user = $this->user();

        if ($user) {
            return $user->current->travelTimeTo($planet);
        }

        return 0;
    }

    /**
     * Get the authenticated user.
     *
     * @return \App\Models\User
     */
    protected function user()
    {
        return $this->auth->guard()->user();
    }
}
