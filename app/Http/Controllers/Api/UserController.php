<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Http\Requests\User\UserStoreRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    #[Post(
        path: '/users',
        summary: 'Register user',
        description: 'Register a new user',
        tags: ['Users'],
        requestBody: new RequestBody(
            description: 'User registration data',
            required: true,
            content: new JsonContent(ref: "#/components/schemas/registerUser")
        ),
    )]
    #[Response(
        response: 200,
        description: 'User registered successfully',
        content: new JsonContent(ref: "#/components/schemas/user")
    )]
    public function register(UserStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->userService->register(collect($validated));

        return response()->json($user);
    }
}
