<?php

/** @var Illuminate\Routing\Router $router */
$router->group([
    'as' => 'api_',
    'namespace' => 'Api',
], function () use ($router) {
    $router->group([
        'prefix' => 'battle-log',
    ], function () use ($router) {
        $router->get('/', 'BattleLogController@index')
            ->name('battle_log');
    });

    $router->group([
        'prefix' => 'bookmark',
    ], function () use ($router) {
        $router->get('/', 'BookmarkController@index')
            ->name('bookmark');

        $router->post('{star}', 'BookmarkController@store')
            ->name('bookmark_store')
            ->where('star', '\d+');

        $router->delete('{bookmark}', 'BookmarkController@destroy')
            ->name('bookmark_destroy')
            ->where('bookmark', '\d+');
    });

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

        $router->get('capital', 'PlanetController@capital')
            ->name('planet_capital');

        $router->get('{planet}', 'PlanetController@show')
            ->name('planet_show')
            ->where('planet', '\d+');

        $router->put('name', 'PlanetController@updateName')
            ->name('planet_name_update');

        $router->delete('demolish/{grid}', 'PlanetController@demolish')
            ->name('planet_demolish')
            ->where('grid', '\d+');
    });

    $router->group([
        'prefix' => 'research',
    ], function () use ($router) {
        $router->get('/', 'ResearchController@index')
            ->name('research');

        $router->post('/resource', 'ResearchController@storeResource')
            ->name('research_resource_store');

        $router->post('{unit}', 'ResearchController@storeUnit')
            ->name('research_unit_store')
            ->where('unit', '\d+');

        $router->delete('/resource', 'ResearchController@destroyResource')
            ->name('research_resource_destroy');

        $router->delete('{unit}', 'ResearchController@destroyUnit')
            ->name('research_unit_destroy')
            ->where('unit', '\d+');
    });

    $router->group([
        'prefix' => 'star',
    ], function () use ($router) {
        $router->get('{star}', 'StarController@show')
            ->name('star_show')
            ->where('star', '\d+');
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
        'prefix' => 'expedition',
    ], function () use ($router) {
        $router->get('/', 'ExpeditionController@index')
            ->name('expedition');
    });

    $router->group([
        'prefix' => 'mission',
    ], function () use ($router) {
        $router->get('/', 'MissionController@index')
            ->name('mission');

        $router->post('{mission}', 'MissionController@store')
            ->name('mission_store')
            ->where('mission', '\d+');
    });

    $router->group([
        'prefix' => 'mission-log',
    ], function () use ($router) {
        $router->get('/', 'MissionLogController@index')
            ->name('mission_log');
    });

    $router->group([
        'prefix' => 'movement',
    ], function () use ($router) {
        $router->post('scout/{planet}', 'MovementController@storeScout')
            ->name('movement_scout_store')
            ->where('planet', '\d+');

        $router->post('attack/{planet}', 'MovementController@storeAttack')
            ->name('movement_attack_store')
            ->where('planet', '\d+');

        $router->post('occupy/{planet}', 'MovementController@storeOccupy')
            ->name('movement_occupy_store')
            ->where('planet', '\d+');

        $router->post('support/{planet}', 'MovementController@storeSupport')
            ->name('movement_support_store')
            ->where('planet', '\d+');

        $router->post('transport/{planet}', 'MovementController@storeTransport')
            ->name('movement_transport_store')
            ->where('planet', '\d+');

        $router->post('trade/{grid}', 'MovementController@storeTrade')
            ->name('movement_trade_store')
            ->where('grid', '\d+');

        $router->post('patrol/{grid}', 'MovementController@storePatrol')
            ->name('movement_patrol_store')
            ->where('grid', '\d+');
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
        'prefix' => 'producer',
    ], function () use ($router) {
        $router->get('{grid}', 'ProducerController@index')
            ->name('producer')
            ->where('grid', '\d+');

        $router->post('{grid}/{resource}', 'ProducerController@store')
            ->name('producer_store')
            ->where('grid', '\d+')
            ->where('resource', '\d+');
    });

    $router->get('scout/{grid}', 'ScoutController@index')
        ->name('scout')
        ->where('grid', '\d+');

    $router->group([
        'prefix' => 'trainer',
    ], function () use ($router) {
        $router->get('{grid}', 'TrainerController@index')
            ->name('trainer')
            ->where('grid', '\d+');

        $router->post('{grid}/{unit}', 'TrainerController@store')
            ->name('trainer_store')
            ->where('grid', '\d+')
            ->where('unit', '\d+');

        $router->delete('{grid}', 'TrainerController@destroy')
            ->name('trainer_destroy')
            ->where('grid', '\d+');
    });

    $router->group([
        'prefix' => 'user',
    ], function () use ($router) {
        $router->get('/', 'UserController@index')
            ->name('user');

        $router->get('capital', 'UserController@capital')
            ->name('user_capital');

        $router->get('trophy', 'UserController@trophy')
            ->name('user_trophy');

        $router->get('{user}', 'UserController@show')
            ->name('user_show');

        $router->put('/', 'UserController@update')
            ->name('user_update');

        $router->put('capital/{planet}', 'UserController@updateCapital')
            ->name('user_capital_update')
            ->where('planet', '\d+');

        $router->put('current/{planet}', 'UserController@updateCurrent')
            ->name('user_current_update')
            ->where('planet', '\d+');
    });
});
