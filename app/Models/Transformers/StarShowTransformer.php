<?php

namespace Koodilab\Models\Transformers;

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
     * @param \Koodilab\Models\Star $item
     */
    public function transform($item)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        return [
            'id' => $item->id,
            'isBookmarked' => $user && Bookmark::findByStarAndUser($item, $user),
            'hasExpedition' => $user && Expedition::findByStarAndUser($item, $user),
        ];
    }
}
