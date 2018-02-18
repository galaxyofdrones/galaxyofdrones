<?php

/** @var Illuminate\Routing\Router $router */
$router->group([
    'namespace' => 'Web',
], function () use ($router) {
    $router->group([
        'prefix' => 'start',
    ], function () use ($router) {
        $router->post('/', 'StartController@store')
            ->name('start_store');

        $router->get('/', 'StartController@index')
            ->name('start');
    });

    $router->group([
        'prefix' => 'starmap',
    ], function () use ($router) {
        $router->get('/', 'StarmapController@index')
            ->name('starmap');
    });

    $router->group([
        'prefix' => 'register',
    ], function () use ($router) {
        $router->get('/', 'RegisterController@showRegistrationForm')
            ->name('register');

        $router->post('/', 'RegisterController@register')
            ->name('register');
    });

    $router->group([
        'prefix' => 'password',
    ], function () use ($router) {
        $router->get('reset', 'ForgotPasswordController@showLinkRequestForm')
            ->name('password.request');

        $router->post('email', 'ForgotPasswordController@sendResetLinkEmail')
            ->name('password.email');

        $router->get('password/reset/{token}', 'ResetPasswordController@showResetForm')
            ->name('password.reset');

        $router->post('reset', 'ResetPasswordController@reset')
            ->name('password.update');
    });

    $router->group([
        'prefix' => 'login',
    ], function () use ($router) {
        $router->get('/', 'LoginController@showLoginForm')
            ->name('login');

        $router->post('/', 'LoginController@login')
            ->name('login');
    });

    $router->get('logout', 'LoginController@logout')
        ->name('logout');

    $router->get('/', 'SurfaceController@index')
        ->name('home');
});
