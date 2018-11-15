<?php

namespace Koodilab\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();

        // Horizon::routeSmsNotificationsTo('15556667777');
        // Horizon::routeMailNotificationsTo('example@example.com');
        // Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function gate()
    {
        Gate::define('viewHorizon', function ($user) {
            return $user->email == env('HORIZON_EMAIL');
        });
    }
}
