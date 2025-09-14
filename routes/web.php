<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs', function () {
    $url = url('api/v1/docs');

    return view('swagger-ui', compact('url'));
});
