<?php

$translations = implode('|', config('app.locales', []));

/* @var Illuminate\Routing\Router $router */
$router->group([
    'as' => 'admin_',
    'prefix' => 'admin',
    'namespace' => 'Admin',
], function () use ($router, $translations) {
    $router->group([
        'prefix' => 'user',
    ], function () use ($router) {
        $router->get('/', 'UserController@index')
            ->name('user');

        $router->get('data', 'UserController@data')
            ->name('user_data');

        $router->get('create', 'UserController@create')
            ->name('user_create');

        $router->post('/', 'UserController@store')
            ->name('user_store');

        $router->get('{user}/edit', 'UserController@edit')
            ->name('user_edit')
            ->where('user', '\d+');

        $router->put('{user}', 'UserController@update')
            ->name('user_update')
            ->where('user', '\d+');

        $router->delete('/', 'UserController@destroy')
            ->name('user_destroy');
    });

    $router->group([
        'prefix' => 'setting',
    ], function () use ($router, $translations) {
        $router->get('edit/{translation}', 'SettingController@edit')
            ->name('setting_edit')
            ->where('translation', $translations);

        $router->put('{translation}', 'SettingController@update')
            ->name('setting_update')
            ->where('translation', $translations);
    });

    $router->group([
        'prefix' => 'profile',
    ], function () use ($router) {
        $router->get('edit', 'ProfileController@edit')
            ->name('profile_edit');

        $router->put('/', 'ProfileController@update')
            ->name('profile_update');
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

    $router->get('overview/data', 'HomeController@overviewData')
        ->name('home_overview_data');

    $router->get('/', 'HomeController@index')
        ->name('home');
});
