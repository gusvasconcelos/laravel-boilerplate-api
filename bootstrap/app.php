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
            return (
                new ErrorResponse($e, __('errors.validation'), 422, 'VALIDATION', $e->errors())
            )->toJson();
        });

        $exceptions->render(function (InvalidArgumentException $e) {
            return (
                new ErrorResponse($e, $e->getMessage(), 422, 'INVALID_ARGUMENT')
            )->toJson();
        });

        $exceptions->render(function (AuthorizationException $e) {
            return (
                new ErrorResponse($e, __('errors.unauthorized'), 403, 'FORBIDDEN')
            )->toJson();
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            $model = $e->getModel();

            $ids = implode(', ', $e->getIds());

            return (
                new ErrorResponse($e, __('errors.resource_not_found', ['resource' => $model]), 404, 'RESOURCE_NOT_FOUND', ['IDs: ' => $ids])
            )->toJson();
        });

        $exceptions->render(function (QueryException $e) {
            return (
                new ErrorResponse($e, __('errors.query_not_acceptable'), 406, 'QUERY_NOT_ACCEPTABLE')
            )->toJson();
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $req) {
            $path = $req->path();

            return (
                new ErrorResponse($e, __('errors.route_not_found', ['route' => $path]), 404, 'RESOURCE_NOT_FOUND')
            )->toJson();
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $req) {
            $method = $req->method();
            $path = $req->path();
            $allowedMethods = $e->getHeaders()['Allow'];

            return (
                new ErrorResponse(
                    $e,
                    __('errors.method_not_allowed', ['method' => $method, 'route' => $path, 'allowedMethods' => $allowedMethods]),
                    405,
                    'METHOD_NOT_ALLOWED'
                )
            )->toJson();
        });

        $exceptions->render(function (TokenInvalidException $e) {
            return (
                new ErrorResponse($e, __('errors.auth_invalid_token'), 403, 'AUTH_INVALID_TOKEN')
            )->toJson();
        });

        $exceptions->render(function (TokenExpiredException $e) {
            return (
                new ErrorResponse($e, __('errors.auth_token_expired'), 401, 'AUTH_EXPIRED_TOKEN')
            )->toJson();
        });

        $exceptions->render(function (JWTException $e) {
            return (
                new ErrorResponse(
                    $e,
                    __('errors.auth_jwt_error'),
                    401,
                    'AUTH_JWT_ERROR'
                )
            )->toJson();
        });

        $exceptions->render(function (HttpException $e) {
            return (
                new ErrorResponse($e, $e->getMessage(), $e->getStatusCode(), $e->getErrorCode(), $e->getDetails())
            )->toJson();
        });

        $exceptions->render(function (Throwable $e) {
            return (
                new ErrorResponse($e, __('errors.internal_server'), 500, 'INTERNAL_SERVER')
            )->toJson();
        });
    })->create();
