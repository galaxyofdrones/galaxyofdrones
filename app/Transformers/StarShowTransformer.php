<?php

namespace App\Transformers;

use App\Models\Bookmark;
use App\Models\Expedition;
use Illuminate\Contracts\Auth\Factory as Auth;

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
     * @param \App\Models\Star $item
     */
    public function transform($item)
    {
        /** @var \App\Models\User $user */
        $user = $this->auth->guard()->user();

        $expedition = Expedition::findByStarAndUser($item, $user);

        return [
            'id' => $item->id,
            'is_bookmarked' => $user && Bookmark::findByStarAndUser($item, $user),
            'has_expedition' => $user && $expedition && ! $expedition->isExpired(),
        ];
    }
}
