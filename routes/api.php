<?php

use App\Http\Controllers\Api\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1'
], function () {
    require_once __DIR__ . '/api/auth.php';

    Route::get('docs', [SwaggerController::class, 'get']);
});
