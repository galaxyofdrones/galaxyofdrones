<?php

namespace Koodilab\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [
            \Koodilab\Listeners\UserLoginListener::class,
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();

        Passport::routes();
    }
}
