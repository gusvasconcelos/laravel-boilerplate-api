<?php

use Illuminate\Http\Request;
use App\Exceptions\HttpException;
use Illuminate\Foundation\Application;
use App\Helpers\Response\ErrorResponse;
use Illuminate\Database\QueryException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReport([
            InvalidArgumentException::class,
            HttpException::class,
            JWTException::class,
        ]);

        $exceptions->render(function (ValidationException $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.validation'),
                statusCode: 422,
                errorCode: 'VALIDATION',
                details: $e->errors()
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (InvalidArgumentException $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: $e->getMessage(),
                statusCode: 422,
                errorCode: 'INVALID_ARGUMENT'
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (AuthorizationException $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.unauthorized'),
                statusCode: 403,
                errorCode: 'FORBIDDEN'
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            $model = $e->getModel();

            $ids = implode(', ', $e->getIds());

            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.resource_not_found', ['resource' => $model]),
                statusCode: 404,
                errorCode: 'RESOURCE_NOT_FOUND',
                details: ['IDs: ' => $ids]
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (QueryException $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.query_not_acceptable'),
                statusCode: 406,
                errorCode: 'QUERY_NOT_ACCEPTABLE'
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $req) {
            $path = $req->path();

            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.route_not_found', ['route' => $path]),
                statusCode: 404,
                errorCode: 'RESOURCE_NOT_FOUND'
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $req) {
            $method = $req->method();
            $path = $req->path();
            $allowedMethods = $e->getHeaders()['Allow'];

            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.method_not_allowed', ['method' => $method, 'route' => $path, 'allowedMethods' => $allowedMethods]),
                statusCode: 405,
                errorCode: 'METHOD_NOT_ALLOWED'
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (TokenInvalidException $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.auth_invalid_token'),
                statusCode: 403,
                errorCode: 'AUTH_INVALID_TOKEN'
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (TokenExpiredException $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.auth_token_expired'),
                statusCode: 401,
                errorCode: 'AUTH_EXPIRED_TOKEN'
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (TokenExpiredException $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.auth_token_expired'),
                statusCode: 401,
                errorCode: 'AUTH_EXPIRED_TOKEN'
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (JWTException $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.auth_jwt_error'),
                statusCode: 401,
                errorCode: 'AUTH_JWT_ERROR'
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (HttpException $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: $e->getMessage(),
                statusCode: $e->getStatusCode(),
                errorCode: $e->getErrorCode(),
                details: $e->getDetails()
            );

            return $errorResponse->toJson();
        });

        $exceptions->render(function (Throwable $e) {
            $errorResponse = new ErrorResponse(
                exception: $e,
                message: __('errors.internal_server'),
                statusCode: 500,
                errorCode: 'INTERNAL_SERVER'
            );

            return $errorResponse->toJson();
        });
    })->create();
