<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1'
], function () {
    Route::get('docs', [SwaggerController::class, 'get']);

    Route::group([
        'middleware' => 'api',
        'prefix' => 'auth',
    ], function () {
        Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('api');
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});
