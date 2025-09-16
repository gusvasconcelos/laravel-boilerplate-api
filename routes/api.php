<?php

use App\Http\Controllers\Api\SwaggerController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function () {
    require_once __DIR__ . '/api/auth.php';

    Route::get('docs', [SwaggerController::class, 'get']);

    Route::post('users', [UserController::class, 'register']);

    Route::group([
        'middleware' => ['jwt'],
    ], function () {
        Route::post('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
        Route::post('email/send', [EmailVerificationController::class, 'send']);
    });
});
