<?php

namespace Koodilab\Transformers;

use Illuminate\Contracts\Auth\Factory as Auth;

class UnitAvailableTransformer extends UnitTransformer
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
     * @param \Koodilab\Models\Unit $item
     */
    public function transform($item)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $research = $user
            ? $item->findResearchByUser($user)
            : null;

        return array_merge(parent::transform($item), [
            'remaining' => $research
                ? $research->remaining
                : null,
        ]);
    }
}
