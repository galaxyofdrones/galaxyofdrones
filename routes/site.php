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

    $router->get('logout', 'LoginController@logout')
        ->name('logout');

    $router->get('/', 'HomeController@index')
        ->name('home');
});
