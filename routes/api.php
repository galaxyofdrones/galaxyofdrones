<?php

/** @var Illuminate\Routing\Router $router */
$router->group([
    'as' => 'api_',
    'namespace' => 'Api',
], function () use ($router) {
    $router->group([
        'prefix' => 'planet',
    ], function () use ($router) {
        $router->get('current', 'PlanetController@current')
            ->name('planet_current');

        $router->put('name', 'PlanetController@name')
            ->name('planet_name');
    });

    $router->group([
        'prefix' => 'starmap',
    ], function () use ($router) {
        $router->get('geo-json/{zoom}/{bounds}', 'StarmapController@geoJson')
            ->name('starmap_geo_json')
            ->where('zoom', '\d')
            ->where('bounds', '[-0-9\.,]+');
    });

    $router->group([
        'prefix' => 'user',
    ], function () use ($router) {
        $router->put('current/{planet}', 'UserController@current')
            ->name('user_current')
            ->where('planet', '\d+');
    });
});
