<?php

namespace App\Transformers;

use App\Models\Planet;
use Illuminate\Contracts\Auth\Factory as Auth;

class PlanetFeatureTransformer extends Transformer
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Constructor.
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * {@inheritdoc}
     *
     * @param Planet $item
     */
    public function transform($item)
    {
        return [
            'type' => 'Feature',
            'properties' => [
                'id' => $item->id,
                'name' => $item->display_name,
                'type' => 'planet',
                'size' => 32 + ($item->size * 16),
                'status' => $this->status($item),
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    $item->x, $item->y,
                ],
            ],
        ];
    }

    /**
     * Get the status.
     *
     * @return string
     */
    protected function status(Planet $planet)
    {
        if (! $planet->user_id) {
            return 'free';
        }

        /** @var \App\Models\User $user */
        $user = $this->auth->guard()->user();

        if ($user) {
            if ($planet->id == $user->current_id) {
                return 'current';
            } elseif ($planet->user_id == $user->id) {
                return 'friendly';
            }
        }

        if ($planet->id == $planet->user->capital_id) {
            return 'capital';
        }

        return 'hostile';
    }
}
