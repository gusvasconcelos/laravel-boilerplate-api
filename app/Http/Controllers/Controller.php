<?php

namespace App\Http\Controllers;

use OpenApi\Attributes\Info;
use OpenApi\Attributes\Server;
use OpenApi\Attributes\SecurityScheme;
use OpenApi\Attributes\Tag;

#[Info(
    version: '1.0.0',
    title: 'Laravel Boilerplate API',
    description: 'Laravel Boilerplate API Documentation'
)]
#[Server(
    url: '/api/v1',
    description: 'Laravel Boilerplate API V1 Server'
)]
#[SecurityScheme(
    securityScheme: 'token',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Token JWT for authentication. Add the token without the "Bearer" prefix'
)]
#[Tag(
    name: 'Authentication',
    description: 'Authentication endpoints'
)]
abstract class Controller
{
    //
}
