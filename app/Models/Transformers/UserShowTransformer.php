<?php

namespace Koodilab\Models\Transformers;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Translation\Translator;
use Koodilab\Models\Planet;
use Koodilab\Models\User;

class UserShowTransformer extends Transformer
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
     * @param \Koodilab\Models\User $item
     */
    public function transform($item)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        return [
            'id' => $item->id,
            'username' => $item->username,
            'username_with_level' => $this->translator->trans('messages.user.username_with_level', [
                'username' => $item->username,
                'level' => $item->level,
            ]),
            'experience' => $item->experience,
            'mission_count' => $item->missionLogs()->count(),
            'expedition_count' => $item->expeditionLogs()->count(),
            'planet_count' => $item->planets()->count(),
            'winning_battle_count' => $item->winningBattleLogCount(),
            'losing_battle_count' => $item->losingBattleLogCount(),
            'created_at' => $item->created_at->toDateTimeString(),
            'canBlock' => $user->id != $item->id,
            'isBlocked' => ! empty($user->findByBlocked($item)),
            'isBlockedBy' => ! empty($item->findByBlocked($user)),
            'planets' => $this->planets($item),
        ];
    }

    /**
     * Get the planets.
     *
     * @param User $user
     *
     * @return array
     */
    public function planets(User $user)
    {
        return $user->findPlanetsOrderByName()
            ->transform(function (Planet $planet) {
                return [
                    'id' => $planet->id,
                    'resource_id' => $planet->resource_id,
                    'name' => $planet->display_name,
                    'x' => $planet->x,
                    'y' => $planet->y,
                ];
            });
    }
}
