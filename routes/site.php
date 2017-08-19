<?php

/** @var Illuminate\Routing\Router $router */
$router->group([
    'namespace' => 'Site',
], function () use ($router) {
    $router->get('/', 'HomeController@index')
        ->name('home');
});
