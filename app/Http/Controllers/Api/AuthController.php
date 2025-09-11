<?php

namespace App\Http\Controllers\Api;

use App\Services\Auth\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {

    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $data = $this->authService->login(collect($validated));

        return response()->json([
            'access_token' => $data,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function me(): JsonResponse
    {
        $data = $this->authService->me();

        return response()->json($data);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => __('messages.auth.logout')
        ]);
    }

    public function refresh(): JsonResponse
    {
        $data = $this->authService->refresh();

        return response()->json([
            'access_token' => $data,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
