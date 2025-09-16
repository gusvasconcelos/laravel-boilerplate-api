<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use App\Services\User\UserService;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;

class EmailVerificationController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    #[Post(
        path: '/email/verify/{id}/{hash}',
        summary: 'Verify email',
        description: 'Verify the email of the authenticated user',
        tags: ['Users'],
        security: [['bearer-token' => []]],
        parameters: [
            new Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'User ID'
            ),
            new Parameter(
                name: 'hash',
                in: 'path',
                required: true,
                description: 'Email verification hash'
            )
        ]
    )]
    #[Response(
        response: 200,
        description: 'Email verified successfully',
        content: new JsonContent(
            title: 'message',
            description: 'Email verified successfully',
            type: 'object',
            required: ['message'],
            example: ['message' => 'Email verified successfully']
        )
    )]
    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        $message = $this->userService->verifyEmail();

        return response()->json($message);
    }

    #[Post(
        path: '/email/send',
        summary: 'Send email verification',
        description: 'Send an email verification to the authenticated user',
        tags: ['Users'],
        security: [['bearer-token' => []]],
    )]
    #[Response(
        response: 200,
        description: 'Email verification sent successfully',
        content: new JsonContent(
            title: 'message',
            description: 'Email verification sent successfully',
            type: 'object',
            required: ['message'],
            example: ['message' => 'Email verification sent successfully']
        )
    )]
    public function send(): JsonResponse
    {
        $user = auth('api')->user();

        $message = $this->userService->sendEmailVerificationNotification($user);

        return response()->json($message);
    }
}
