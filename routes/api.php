<?php

/** @var Illuminate\Routing\Router $router */
$router->group([
    'as' => 'api_',
    'namespace' => 'Api',
], function () use ($router) {
    $router->group([
        'prefix' => 'construction',
    ], function () use ($router) {
        $router->get('{grid}', 'ConstructionController@index')
            ->name('construction')
            ->where('grid', '\d+');

        $router->post('{grid}/{building}', 'ConstructionController@store')
            ->name('construction_store')
            ->where('grid', '\d+')
            ->where('building', '\d+');

        $router->delete('{grid}', 'ConstructionController@destroy')
            ->name('construction_destroy')
            ->where('grid', '\d+');
    });

    $router->group([
        'prefix' => 'planet',
    ], function () use ($router) {
        $router->get('/', 'PlanetController@index')
            ->name('planet');

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
        $router->get('/', 'UserController@index')
            ->name('user');

        $router->put('current/{planet}', 'UserController@current')
            ->name('user_current')
            ->where('planet', '\d+');
    });
});
