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
        'prefix' => 'upgrade',
    ], function () use ($router) {
        $router->get('{grid}', 'UpgradeController@index')
            ->name('upgrade')
            ->where('grid', '\d+');

        $router->post('{grid}', 'UpgradeController@store')
            ->name('upgrade_store')
            ->where('grid', '\d+');

        $router->delete('{grid}', 'UpgradeController@destroy')
            ->name('upgrade_destroy')
            ->where('grid', '\d+');
    });

    $router->group([
        'prefix' => 'training',
    ], function () use ($router) {
        $router->get('{grid}', 'TrainingController@index')
            ->name('training')
            ->where('grid', '\d+');

        $router->post('{grid}/{unit}', 'TrainingController@store')
            ->name('training_store')
            ->where('grid', '\d+')
            ->where('unit', '\d+');

        $router->delete('{grid}', 'TrainingController@destroy')
            ->name('training_destroy')
            ->where('grid', '\d+');
    });

    $router->group([
        'prefix' => 'transmute',
    ], function () use ($router) {
        $router->get('{grid}', 'TransmuteController@index')
            ->name('transmute')
            ->where('grid', '\d+');

        $router->post('{grid}/{resource}', 'TransmuteController@store')
            ->name('transmute_store')
            ->where('grid', '\d+')
            ->where('resource', '\d+');
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
