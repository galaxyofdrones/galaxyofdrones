<?php

use App\Http\Controllers\Web\ForgotPasswordController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\RegisterController;
use App\Http\Controllers\Web\ResetPasswordController;
use App\Http\Controllers\Web\StartController;
use App\Http\Controllers\Web\VerificationController;

Route::group([
    'prefix' => 'start',
], function () {
    Route::post('/', [StartController::class, 'store'])
        ->name('start_store');

    Route::get('/', [StartController::class, 'index'])
        ->name('start');
});

Route::group([
    'prefix' => 'register',
], function () {
    Route::get('/', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('/', [RegisterController::class, 'register'])
        ->name('register');
});

Route::group([
    'prefix' => 'password',
], function () {
    Route::get('reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('reset', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

Route::group([
    'prefix' => 'email',
], function () {
    Route::get('verify', [VerificationController::class, 'show'])
        ->name('verification.notice');

    Route::get('verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->name('verification.verify');

    Route::post('resend', [VerificationController::class, 'resend'])
        ->name('verification.resend');

    Route::post('update', [VerificationController::class, 'update'])
        ->name('verification.update');
});

Route::group([
    'prefix' => 'login',
], function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/', [LoginController::class, 'login'])
        ->name('login');
});

Route::get('logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::get('/{vue?}', [HomeController::class, 'index'])
    ->name('home')
    ->where('vue', 'starmap');
