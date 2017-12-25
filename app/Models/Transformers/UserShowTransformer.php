<?php

namespace Koodilab\Models\Transformers;

use Illuminate\Contracts\Translation\Translator;

class UserShowTransformer extends Transformer
{
    /**
     * The translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\User $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'username' => $item->username,
            'username_with_level' => $this->translator->trans('messages.user.username_with_level', [
                'username' => $item->username,
                'level' => $item->level,
            ]),
            'experience' => $item->experience,
            'mission_count' => $item->missionLogs()->count(),
            'winning_battle_count' => $item->winningBattleLogCount(),
            'losing_battle_count' => $item->losingBattleLogCount(),
            'created_at' => $item->created_at->toDateString(),
        ];
    }
}
