<?php

namespace Koodilab\Transformers;

use Illuminate\Contracts\Auth\Factory as Auth;
use Koodilab\Models\Bookmark;
use Koodilab\Models\Expedition;

class StarShowTransformer extends Transformer
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
     * @param \Koodilab\Models\Star $item
     */
    public function transform($item)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        $expedition = Expedition::findByStarAndUser($item, $user);

        return [
            'id' => $item->id,
            'is_bookmarked' => $user && Bookmark::findByStarAndUser($item, $user),
            'has_expedition' => $user && $expedition && ! $expedition->isExpired(),
        ];
    }
}
