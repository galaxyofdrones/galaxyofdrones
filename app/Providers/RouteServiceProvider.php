<?php

namespace Koodilab\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Koodilab\Models\User;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $namespace = 'Koodilab\Http\Controllers';

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();

        Route::bind('user', function ($value) {
            return User::findByIdOrUsername($value);
        });
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
