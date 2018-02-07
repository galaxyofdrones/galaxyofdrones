<?php

namespace Koodilab\Support;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Session\SessionManager;

class FlashManager
{
    /**
     * The session key.
     *
     * @var string
     */
    const SESSION_KEY = '_flash_manager';

    /**
     * The info type.
     *
     * @var string
     */
    const TYPE_INFO = 'info';

    /**
     * The success type.
     *
     * @var string
     */
    const TYPE_SUCCESS = 'success';

    /**
     * The error type.
     *
     * @var string
     */
    const TYPE_ERROR = 'error';

    /**
     * The session manager instance.
     *
     * @var SessionManager|\Illuminate\Session\Store
     */
    protected $session;

    /**
     * The translator implementation.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Constructor.
     *
     * @param SessionManager $session
     * @param Translator     $translator
     */
    public function __construct(SessionManager $session, Translator $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * Create an info flash message.
     *
     * @param string $message
     * @param string $title
     */
    public function info($message, $title = null)
    {
        if (! $title) {
            $title = $this->translator->trans('messages.info');
        }

        $this->message($title, $message, static::TYPE_INFO);
    }

    /**
     * Create a success flash message.
     *
     * @param string $message
     * @param string $title
     */
    public function success($message, $title = null)
    {
        if (! $title) {
            $title = $this->translator->trans('messages.success.singular');
        }

        $this->message($title, $message, static::TYPE_SUCCESS);
    }

    /**
     * Create an error flash message.
     *
     * @param string $message
     * @param string $title
     */
    public function error($message, $title = null)
    {
        if (! $title) {
            $title = $this->translator->trans('messages.error.whoops');
        }

        $this->message($title, $message, static::TYPE_ERROR);
    }

    /**
     * Create a flash message.
     *
     * @param string $title
     * @param string $message
     * @param string $type
     */
    protected function message($title, $message, $type)
    {
        $this->session->flash(static::SESSION_KEY, [
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }
}
