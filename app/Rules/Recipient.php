<?php

namespace Koodilab\Rules;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Rule;
use Koodilab\Models\User;

class Recipient implements Rule
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * The translator implementation.
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
     */
    public function passes($attribute, $value)
    {
        $recipient = User::findByUsername($value);

        if (! $recipient) {
            return false;
        }

        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        if ($user->findByBlocked($recipient)) {
            return false;
        }

        if ($recipient->findByBlocked($user)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function message()
    {
        return $this->translator->trans('validation.exists');
    }
}
