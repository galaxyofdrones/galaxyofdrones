<?php

namespace App\Providers;

use App\Listeners\UserLoginListener;
use App\Models\Grid;
use App\Models\Planet;
use App\Models\Setting;
use App\Models\User;
use App\Observers\GridObserver;
use App\Observers\PlanetObserver;
use App\Observers\SettingObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
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

        Registered::class => [
            SendEmailVerificationNotification::class,
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
