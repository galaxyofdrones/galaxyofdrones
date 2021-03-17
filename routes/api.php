<?php

use App\Http\Controllers\Api\BattleLogController;
use App\Http\Controllers\Api\BlockController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\ConstructionController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\ExpeditionController;
use App\Http\Controllers\Api\ExpeditionLogController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\MissionController;
use App\Http\Controllers\Api\MissionLogController;
use App\Http\Controllers\Api\MonitorController;
use App\Http\Controllers\Api\MovementController;
use App\Http\Controllers\Api\PlanetController;
use App\Http\Controllers\Api\ProducerController;
use App\Http\Controllers\Api\RankController;
use App\Http\Controllers\Api\ResearchController;
use App\Http\Controllers\Api\ScoutController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShieldController;
use App\Http\Controllers\Api\StarController;
use App\Http\Controllers\Api\StarmapController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\UpgradeController;
use App\Http\Controllers\Api\UserController;

Route::group([
    'as' => 'api_',
    'namespace' => 'Api',
], function () {
    Route::group([
        'prefix' => 'battle-log',
    ], function () {
        Route::get('/', [BattleLogController::class, 'index'])
            ->name('battle_log');
    });

    Route::group([
        'prefix' => 'bookmark',
    ], function () {
        Route::get('/', [BookmarkController::class, 'index'])
            ->name('bookmark');

        Route::post('{star}', [BookmarkController::class, 'store'])
            ->name('bookmark_store')
            ->where('star', '\d+');

        Route::delete('{bookmark}', [BookmarkController::class, 'destroy'])
            ->name('bookmark_destroy')
            ->where('bookmark', '\d+');
    });

    Route::group([
        'prefix' => 'shield',
    ], function () {
        Route::get('/', [ShieldController::class, 'index'])
            ->name('shield');

        Route::post('{planet}', [ShieldController::class, 'store'])
            ->name('shield_store')
            ->where('planet', '\d+');
    });

    Route::group([
        'prefix' => 'monitor',
    ], function () {
        Route::get('/', [MonitorController::class, 'index'])
            ->name('monitor');

        Route::get('show', [MonitorController::class, 'show'])
            ->name('monitor_show');
    });

    Route::group([
        'prefix' => 'block',
    ], function () {
        Route::put('{user}', [BlockController::class, 'update'])
            ->name('block_update');
    });

    Route::group([
        'prefix' => 'message',
    ], function () {
        Route::get('/', [MessageController::class, 'index'])
            ->name('message');

        Route::post('/', [MessageController::class, 'store'])
            ->name('message_store');
    });

    Route::group([
        'prefix' => 'construction',
    ], function () {
        Route::get('{grid}', [ConstructionController::class, 'index'])
            ->name('construction')
            ->where('grid', '\d+');

        Route::post('{grid}/{building}', [ConstructionController::class, 'store'])
            ->name('construction_store')
            ->where('grid', '\d+')
            ->where('building', '\d+');

        Route::delete('{grid}', [ConstructionController::class, 'destroy'])
            ->name('construction_destroy')
            ->where('grid', '\d+');
    });

    Route::group([
        'prefix' => 'planet',
    ], function () {
        Route::get('/', [PlanetController::class, 'index'])
            ->name('planet');

        Route::get('all/{user}', [PlanetController::class, 'all'])
            ->name('planet_all');

        Route::get('capital', [PlanetController::class, 'capital'])
            ->name('planet_capital');

        Route::get('{planet}', [PlanetController::class, 'show'])
            ->name('planet_show')
            ->where('planet', '\d+');

        Route::put('name', [PlanetController::class, 'updateName'])
            ->name('planet_name_update');

        Route::delete('demolish/{grid}', [PlanetController::class, 'demolish'])
            ->name('planet_demolish')
            ->where('grid', '\d+');
    });

    Route::group([
        'prefix' => 'research',
    ], function () {
        Route::get('/', [ResearchController::class, 'index'])
            ->name('research');

        Route::post('/resource', [ResearchController::class, 'storeResource'])
            ->name('research_resource_store');

        Route::post('{unit}', [ResearchController::class, 'storeUnit'])
            ->name('research_unit_store')
            ->where('unit', '\d+');

        Route::delete('/resource', [ResearchController::class, 'destroyResource'])
            ->name('research_resource_destroy');

        Route::delete('{unit}', [ResearchController::class, 'destroyUnit'])
            ->name('research_unit_destroy')
            ->where('unit', '\d+');
    });

    Route::group([
        'prefix' => 'star',
    ], function () {
        Route::get('{star}', [StarController::class, 'show'])
            ->name('star_show')
            ->where('star', '\d+');
    });

    Route::group([
        'prefix' => 'starmap',
    ], function () {
        Route::get('geo-json/{zoom}/{bounds}', [StarmapController::class, 'geoJson'])
            ->name('starmap_geo_json')
            ->where('zoom', '\d')
            ->where('bounds', '[-0-9\.,]+');
    });

    Route::group([
        'prefix' => 'expedition',
    ], function () {
        Route::get('/', [ExpeditionController::class, 'index'])
            ->name('expedition');

        Route::post('{expedition}', [ExpeditionController::class, 'store'])
            ->name('expedition_store')
            ->where('expedition', '\d+');
    });

    Route::group([
        'prefix' => 'expedition-log',
    ], function () {
        Route::get('/', [ExpeditionLogController::class, 'index'])
            ->name('expedition_log');
    });

    Route::group([
        'prefix' => 'mission',
    ], function () {
        Route::get('/', [MissionController::class, 'index'])
            ->name('mission');

        Route::post('{mission}', [MissionController::class, 'store'])
            ->name('mission_store')
            ->where('mission', '\d+');
    });

    Route::group([
        'prefix' => 'mission-log',
    ], function () {
        Route::get('/', [MissionLogController::class, 'index'])
            ->name('mission_log');
    });

    Route::group([
        'prefix' => 'movement',
    ], function () {
        Route::post('scout/{planet}', [MovementController::class, 'storeScout'])
            ->name('movement_scout_store')
            ->where('planet', '\d+');

        Route::post('attack/{planet}', [MovementController::class, 'storeAttack'])
            ->name('movement_attack_store')
            ->where('planet', '\d+');

        Route::post('occupy/{planet}', [MovementController::class, 'storeOccupy'])
            ->name('movement_occupy_store')
            ->where('planet', '\d+');

        Route::post('support/{planet}', [MovementController::class, 'storeSupport'])
            ->name('movement_support_store')
            ->where('planet', '\d+');

        Route::post('transport/{planet}', [MovementController::class, 'storeTransport'])
            ->name('movement_transport_store')
            ->where('planet', '\d+');

        Route::post('trade/{grid}', [MovementController::class, 'storeTrade'])
            ->name('movement_trade_store')
            ->where('grid', '\d+');

        Route::post('patrol/{grid}', [MovementController::class, 'storePatrol'])
            ->name('movement_patrol_store')
            ->where('grid', '\d+');
    });

    Route::group([
        'prefix' => 'upgrade',
    ], function () {
        Route::get('{grid}', [UpgradeController::class, 'index'])
            ->name('upgrade')
            ->where('grid', '\d+');

        Route::get('all', [UpgradeController::class, 'indexAll'])
            ->name('upgrade_all');

        Route::post('{grid}', [UpgradeController::class, 'store'])
            ->name('upgrade_store')
            ->where('grid', '\d+');

        Route::post('all', [UpgradeController::class, 'storeAll'])
            ->name('upgrade_store_all');

        Route::delete('{grid}', [UpgradeController::class, 'destroy'])
            ->name('upgrade_destroy')
            ->where('grid', '\d+');
    });

    Route::group([
        'prefix' => 'producer',
    ], function () {
        Route::get('{grid}', [ProducerController::class, 'index'])
            ->name('producer')
            ->where('grid', '\d+');

        Route::post('{grid}/{resource}', [ProducerController::class, 'store'])
            ->name('producer_store')
            ->where('grid', '\d+')
            ->where('resource', '\d+');
    });

    Route::get('scout/{grid}', [ScoutController::class, 'index'])
        ->name('scout')
        ->where('grid', '\d+');

    Route::group([
        'prefix' => 'trainer',
    ], function () {
        Route::get('{grid}', [TrainerController::class, 'index'])
            ->name('trainer')
            ->where('grid', '\d+');

        Route::post('{grid}/{unit}', [TrainerController::class, 'store'])
            ->name('trainer_store')
            ->where('grid', '\d+')
            ->where('unit', '\d+');

        Route::delete('{grid}', [TrainerController::class, 'destroy'])
            ->name('trainer_destroy')
            ->where('grid', '\d+');
    });

    Route::group([
        'prefix' => 'rank',
    ], function () {
        Route::get('pve', [RankController::class, 'pve'])
            ->name('rank_pve');

        Route::get('pvp', [RankController::class, 'pvp'])
            ->name('rank_pvp');
    });

    Route::group([
        'prefix' => 'user',
    ], function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('user');

        Route::get('capital', [UserController::class, 'capital'])
            ->name('user_capital');

        Route::get('{user}', [UserController::class, 'show'])
            ->name('user_show');

        Route::put('/', [UserController::class, 'update'])
            ->name('user_update');

        Route::put('capital/{planet}', [UserController::class, 'updateCapital'])
            ->name('user_capital_update')
            ->where('planet', '\d+');

        Route::put('current/{planet}', [UserController::class, 'updateCurrent'])
            ->name('user_current_update')
            ->where('planet', '\d+');
    });

    Route::put('setting', [SettingController::class, 'update'])
        ->name('setting_update');

    Route::get('status', [StatusController::class, 'index'])
        ->name('status');

    Route::post('donation', [DonationController::class, 'index'])
        ->name('donation');
});
