<?php

namespace Koodilab\Models\Transformers;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Translation\Translator;
use Koodilab\Models\Planet;

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
     *
     * @param Auth       $auth
     * @param Translator $translator
     */
    public function __construct(Auth $auth, Translator $translator)
    {
        $this->auth = $auth;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\Planet $item
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
                : $this->translator->trans('messages.free'),
            'canOccupy' => $this->canOccupy($item),
        ];
    }

    /**
     * Can occupy?
     *
     * @param Planet $planet
     *
     * @return bool
     */
    protected function canOccupy(Planet $planet)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        if ($user) {
            return $user->canOccupy($planet);
        }

        return false;
    }
}
