<?php

namespace App\Transformers;

use App\Models\Movement;
use Illuminate\Contracts\Auth\Factory as Auth;

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
     * @return string
     */
    protected function status(Movement $movement)
    {
        /** @var \App\Models\User $user */
        $user = $this->auth->guard()->user();

        if ($user && $user->current_id == $movement->end_id) {
            return 'incoming';
        }

        return 'outgoing';
    }
}
