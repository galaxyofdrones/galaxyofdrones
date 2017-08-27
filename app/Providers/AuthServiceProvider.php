<?php

namespace Koodilab\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Koodilab\Auth\KoodilabUserProvider;
use Koodilab\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        Auth::provider('koodilab', function ($app, array $config) {
            return $this->userProvider($config);
        });

        $this->registerPolicies();

        Gate::before(function (User $user) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        Gate::define('dashboard', function (User $user) {
            return $user->canUseDashboard();
        });
    }

    /**
     * Create the user provider instance.
     *
     * @param array $config
     *
     * @return KoodilabUserProvider
     */
    protected function userProvider(array $config)
    {
        return new KoodilabUserProvider(
            $this->app->make('hash'), $config['model']
        );
    }
}
