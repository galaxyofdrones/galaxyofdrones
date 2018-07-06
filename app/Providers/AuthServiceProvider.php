<?php

namespace Koodilab\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Koodilab\Auth\KoodilabUserProvider;
use Koodilab\Models\Bookmark;
use Koodilab\Models\Expedition;
use Koodilab\Models\Mission;
use Koodilab\Models\Planet;
use Koodilab\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $policies = [
        Bookmark::class => \Koodilab\Policies\BookmarkPolicy::class,
        Expedition::class => \Koodilab\Policies\ExpeditionPolicy::class,
        Mission::class => \Koodilab\Policies\MissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        Auth::provider('koodilab', function ($app, array $config) {
            return $this->userProvider($config);
        });

        $this->registerPolicies();

        Gate::define('friendly', function (User $user, Planet $planet) {
            return $user->id == $planet->user_id;
        });

        Gate::define('hostile', function (User $user, Planet $planet) {
            return $user->id != $planet->user_id;
        });

        Gate::define('building', function (User $user, $building, $type) {
            return ! empty($building) && $building->type == $type;
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
