<?php

namespace Koodilab\Observers;

use Koodilab\Support\SettingManager;

class SettingObserver
{
    /**
     * The setting manager instance.
     *
     * @var SettingManager
     */
    protected $settingManager;

    /**
     * Constructor.
     *
     * @param SettingManager $settingManager
     */
    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    /**
     * Saved.
     */
    public function saved()
    {
        $this->settingManager->forget();
    }
}
