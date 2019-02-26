<?php

namespace Koodilab\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Koodilab\Listeners\UserLoginListener;
use Koodilab\Models\Grid;
use Koodilab\Models\Planet;
use Koodilab\Models\Setting;
use Koodilab\Models\User;
use Koodilab\Observers\GridObserver;
use Koodilab\Observers\PlanetObserver;
use Koodilab\Observers\SettingObserver;
use Koodilab\Observers\UserObserver;
use Laravel\Passport\Passport;

class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $listen = [
        Login::class => [
            UserLoginListener::class,
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();

        Passport::routes();

        Grid::observe(GridObserver::class);
        Planet::observe(PlanetObserver::class);
        Setting::observe(SettingObserver::class);
        User::observe(UserObserver::class);
    }
}
