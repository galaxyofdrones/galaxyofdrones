<?php

/** @var Illuminate\Routing\Router $router */
$router->group([
    'namespace' => 'Site',
], function () use ($router) {
    $router->group([
        'prefix' => 'login',
    ], function () use ($router) {
        $router->get('/', 'LoginController@showLoginForm')
            ->name('login');

        $router->post('/', 'LoginController@login')
            ->name('login');
    });

    $router->group([
        'prefix' => 'start',
    ], function () use ($router) {
        $router->post('/', 'StartController@store')
            ->name('start_store');

        $router->get('/', 'StartController@index')
            ->name('start');
    });

    $router->get('logout', 'LoginController@logout')
        ->name('logout');

    $router->get('/', 'StarmapController@index')
        ->name('home');
});
