<?php

namespace App\Transformers;

use Illuminate\Contracts\Auth\Factory as Auth;

class UnitExpeditionTransformer extends Transformer
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
     * @param \App\Models\Unit $item
     */
    public function transform($item)
    {
        /** @var \App\Models\User $user */
        $user = $this->auth->guard()->user();

        /** @var \App\Models\Unit $unit */
        $unit = $user->units->firstWhere('id', $item->id);

        return [
            'id' => $item->id,
            'name' => $item->translation('name'),
            'description' => $item->translation('description'),
            'quantity' => $unit
                ? $unit->pivot->quantity
                : 0,
        ];
    }
}
