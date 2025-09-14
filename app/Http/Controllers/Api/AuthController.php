<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UnprocessableEntityException;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\JsonContent;

class AuthController extends Controller
{
    #[Post(
        path: '/auth/login',
        summary: 'Login user',
        description: 'Authenticate a user and return a JWT token',
        tags: ['Authentication'],
        responses: [
            new Response(
                response: 200,
                description: 'Login successful',
                content: new JsonContent(ref: "#/components/schemas/token")
            )
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $token = auth('api')->attempt($validated);

        if (! $token) {
            throw new UnprocessableEntityException(__('messages.auth.invalid_credentials'), 'INVALID_CREDENTIALS');
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 // @phpstan-ignore-line
        ]);
    }

    #[Get(
        path: '/auth/me',
        summary: 'Get authenticated user data',
        description: 'Return the authenticated user data',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new Response(
                response: 200,
                description: 'User data',
                content: new JsonContent(ref: "#/components/schemas/user")
            )
        ]
    )]
    public function me(): JsonResponse
    {
        $user = auth('api')->user();

        if (! $user) {
            throw new UnauthorizedException(__('messages.auth.not_authenticated'));
        }

        return response()->json($user);
    }

    #[Post(
        path: '/auth/logout',
        summary: 'Logout user',
        description: 'Invalidate the authenticated user JWT token',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new Response(
                response: 200,
                description: 'Logout successful',
                content: new JsonContent(ref: "#/components/schemas/logout")
            )
        ]
    )]
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            'message' => __('messages.auth.logout')
        ]);
    }

    #[Post(
        path: '/auth/refresh',
        summary: 'Refresh token JWT',
        description: 'Refresh the authenticated user JWT token',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new Response(
                response: 200,
                description: 'Token refreshed successfully',
                content: new JsonContent(ref: "#/components/schemas/token"),

            )
        ]
    )]
    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh(); // @phpstan-ignore-line

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 // @phpstan-ignore-line
        ]);
    }
}
