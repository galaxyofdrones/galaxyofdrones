<?php

namespace Koodilab\Models\Transformers;

use Illuminate\Contracts\Auth\Factory as Auth;
use Koodilab\Models\Movement;

class MovementFeatureTransformer extends Transformer
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Constructor.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * {@inheritdoc}
     *
     * @param Movement $item
     */
    public function transform($item)
    {
        return [
            'type' => 'Feature',
            'properties' => [
                'type' => $item->type,
                'status' => $this->status($item),
            ],
            'geometry' => [
                'type' => 'LineString',
                'coordinates' => [
                    [
                        $item->start->x,
                        $item->start->y,
                    ],
                    [
                        $item->end->x,
                        $item->end->y,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get the status.
     *
     * @param Movement $movement
     *
     * @return string
     */
    protected function status(Movement $movement)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        if ($user && $user->current_id == $movement->start_id) {
            return 'incoming';
        }

        return 'outgoing';
    }
}
